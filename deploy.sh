#! /bin/sh -e

configName="$1"
host=
dest=
user=
pass=
source="src"
ignore=(config.json medias)

configFileName="./deploy.$1.config"

log(){
	type="$1"

	shift

	case "$type" in
		'done')
			if [ -t 1 ]; then
				echo -e "\e[1;32m> ${@:-done.}\e[0m"
			else
				echo "> ${@:-done.}"
			fi

			;;

		'info')
			if [ -t 1 ]; then
				echo -e "\e[1;37m${@:-unknown info.}\e[0m"
			else
				echo "${@:-unknown info.}"
			fi

			;;

		'task')
			if [ -t 1 ]; then
				echo -e "\e[36m${@:-unknown task...}\e[0m"
			else
				echo "${@:-unknown task...}"
			fi

			;;

		'warn')
			if [ -t 1 ]; then
				echo -e "\e[1;33m! warning: ${@:-unknown warning}\e[0m"
			else
				echo "! warning: ${@:-unknown warning}"
			fi

			;;

		*)
			if [ -t 1 ]; then
				echo -e "\e[1;31m# error: ${@:-unknown error}\e[0m"
			else
				echo "# error: ${@:-unknown error}"
			fi

			;;
	esac
}

containsElement () {
  local e
  for e in "${@:2}"; do [[ "$e" == "$1" ]] && echo 0; done
  echo 1
}

if [ ! -f "$configFileName" ]; then
    log fail "can't find config file ${configFileName}"
    exit 1
fi

. "$configFileName"

log info "Config loaded"
log task "Apply filer [${ignore[@]}]"

file_list=()
 while IFS= read -d $'\0' -r file ; do
     file_list=("${file_list[@]}" "$file")
 done < <(find "$source" -print0)

deploy_filter=()
for elt in $(cd "$source" && echo *); do 
    res=$( containsElement "${elt}" "${ignore[@]}" )

    if [ "${res}" = "1" ]; then
        deploy_filter+=("${source}/${elt}")
    fi
done

log info "Starting upload"

ftp -inv $host << EOF

user $user $pass

cd $dest

mput ${deploy_filter[@]}

bye

EOF

exit 0

deploy_backup="${deploy_backup:-$deploy_link.bak}"
deploy_prefix="${deploy_prefix:-$deploy_link.}"


deploy=
kill=
label=custom
restore=
trafic=

while getopts ":d:hikl:or" option; do
	case "$option" in
		d)
			deploy="$OPTARG"
			;;

		h)
			log info "$(basename $0) [-d <folder>] [-h] [-i] [-k] [-l <label>] [-o] [-r] <host>"
			log task "  -d: deploy given folder to host as '$deploy_path/$deploy_link'"
			log task "  -h: display help"
			log task "  -i: change trafic status to ONLINE (inline)"
			log task "  -k: kill process '$kill_process' after deploy"
			log task "  -l: change label (default: $label)"
			log task "  -o: change trafic status to OFFLINE (outlive)"
			log task "  -r: restore backup on host"
			exit
			;;

		i)
			trafic=ONLINE
			;;

		k)
			kill=1
			;;

		l)
			label="$OPTARG"
			;;

		o)
			trafic=OFFLINE
			;;

		r)
			restore=1
			;;

		:)
			log fail "option '-$OPTARG' requires an argument"
			exit 1
			;;

		*)
			log fail "invalid option '-$OPTARG'"
			exit 1
			;;
	esac
done

shift "$((OPTIND - 1))"

if [ $# -lt 1 ]; then
	log fail "host not specified"
	exit 1
fi

host="$1"

# Deploy folder on target host
if [ -n "$deploy" ]; then
	if [ ! -d "$deploy" ]; then
		log fail "can't read from source folder $deploy"
		exit 1
	fi

	if [ -z "$deploy_filter" ]; then
		deploy_filter=$(cd "$deploy" && echo *)
	fi

	folder="$deploy_prefix$label"
	temp=$(mktemp)

	log task "creating archive from $deploy..."
	tar czf "$temp" -C "$deploy" $deploy_filter

	log task "uploading archive to $host..."
	scp "$temp" "$user@$host:$deploy_path/$deploy_archive"
	rm -f "$temp"

	log task "enabling deployed version..."
	ssh -T "$user@$host" << EOF
cd "$deploy_path"
test -d "$folder" && rm -rf "$folder"
mkdir "$folder"
tar xzf "$deploy_archive" -C "$folder"
rm -f "$deploy_archive"

if [ -e "$deploy_link" ]; then
	if [ -e "$deploy_backup" ]; then
		rm -f "$deploy_link"
	else
		echo "backup for existing link as $deploy_backup..."
		mv "$deploy_link" "$deploy_backup"
	fi
fi

cmd /c mklink /J "$deploy_link" "$folder"
EOF
fi

# Restore backup configuration if found
if [ -n "$restore" ]; then
	log task "restoring backup version..."
	ssh -T "$user@$host" << EOF
cd "$deploy_path"

if [ -e "$deploy_backup" ]; then
	echo "restoring backup $deploy_backup..."

	if [ -e "$deploy_link" ]; then
		rm -f "$deploy_link"
	fi

	mv "$deploy_backup" "$deploy_link"
else
	echo "no backup to restore"
fi
EOF
fi

# Kill process
if [ -n "$kill" ]; then
	log task "terminating process $kill_process..."
	ssh -T "$user@$host" << EOF
ps -eW | tr -s ' ' | cut -d ' ' -f 2,9 | grep -F "$kill_process" | cut -d ' ' -f 1 | while read pid null; do
	echo "kill PID \$pid"

	/bin/kill -f "\$pid"
done
EOF
fi

# Set trafic status on host
if [ -n "$trafic" ]; then
	log task "set trafic status to $trafic..."
	ssh -T "$user@$host" "SETX CRITEO_IIS_APP_STATUS $trafic /M"
fi

log task "done."
