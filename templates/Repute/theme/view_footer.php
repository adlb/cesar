        <!-- FOOTER -->
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <!-- COLUMN 1 -->
                        <h3 class="sr-only"><?php t(':ABOUT_US') ?></h3>
                        <img src="templates/Repute/theme/assets/img/logo/repute-logo-light.png" class="logo" alt="Repute">
                        <p>Proactively aggregate B2B initiatives before extensive channels. Monotonectally extend interactive methods of empowerment through excellent applications. Rapidiously synergize visionary products with sticky technology.</p>
                        <br>
                        <address class="margin-bottom-30px">
                            <ul class="list-unstyled">
                                <li>Association Malte-Liban<br/> Boite Postale 23407</li>
                                <li>75325 Paris Cedex 07</li>
                                <li>Tél : +33 1 58 60 12 50</li>
                                <li>Email: <?php echo $obj['contact'] ?></li>
                            </ul>
                        </address>
                        <!-- END COLUMN 1 -->
                    </div>
                    <div class="col-md-4">
                        <h3 class="footer-heading"><?php t(':USEFUL_LINKS') ?></h3>
                            <ul class="list-unstyled footer-nav">
                                <li>
                                    <a href="http://www.orderofmaltalebanon.org/fr/services">Ordre de Malte - Liban</a>
                                </li>
                                <li>
                                    <a href="http://www.ordredemaltefrance.org">Ordre de Malte - France</a>
                                </li>
                                <li>
                                    <a href="http://www.orderofmalta.int/">Ordre de Malte - International</a>
                                </li>
                                <?php foreach($obj['links'] as $link) { ?>
                                <li>
                                    <a href="<?php echo $link['link']?>"><?php echo $link['display'] ?></a>
                                </li>
                                <?php } ?>
                            </ul>
                        </h3>
                    </div>
                    <div class="col-md-4">
                        <!-- COLUMN 3 -->
                        <div class="newsletter">
                            <h3 class="footer-heading">NEWSLETTER</h3>
                            <p><?php t(':SUBSCRIBE_TO_OUR_NEWSLETTER') ?></p>
                            <form class="newsletter-form" method="POST">
                                <div class="input-group input-group-lg">
                                    <input type="email" class="form-control" name="email" placeholder="youremail@domain.com">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="button"><i class="fa fa-spinner fa-spin"></i><span>SUBSCRIBE</span></button>
                                    </span>
                                </div>
                                <div class="alert"></div>
                            </form>
                        </div>
                        <div class="social-connect">
                            <h3 class="footer-heading">GET CONNECTED</h3>
                            <p><i>Vous avez aimé cet article ? Alors partagez-le avec vos amis en cliquant sur les boutons ci-dessous :</i></p>
                            <ul class="list-inline social-icons">
                                <li>
                                    <a class="twitter-bg" 
                                        target="_blank" 
                                        title="Twitter" 
                                        href="https://twitter.com/share?url=<?php urlencode($obj['url']) ?>&text=<?php urlencode($obj['title']) ?>&via=adlb" 
                                        rel="nofollow" 
                                        onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=700');return false;">
                                        <i class="fa fa-twitter"></i>
                                    </a>
                                </li>
                                <li>
                                    <a class="facebook-bg" 
                                        target="_blank" 
                                        title="Facebook" 
                                        href="https://www.facebook.com/sharer.php?u=<?php urlencode($obj['url']) ?>&t=<?php urlencode($obj['title']) ?>" 
                                        rel="nofollow" 
                                        onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=700');return false;">
                                        <i class="fa fa-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a class="googleplus-bg" 
                                        target="_blank" 
                                        title="Google +" 
                                        href="https://plus.google.com/share?url=<?php urlencode($obj['url']) ?>&hl=fr" 
                                        rel="nofollow" 
                                        onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=450,width=650');return false;">
                                        <i class="fa fa-google-plus"></i>
                                    </a>
                                </li>
                                <li>
                                    <a class="linkedin-bg"
                                        target="_blank" 
                                        title="Linkedin" 
                                        href="https://www.linkedin.com/shareArticle?mini=true&url=<?php urlencode($obj['url']) ?>&title=<?php urlencode($obj['title']) ?>" 
                                        rel="nofollow" 
                                        onclick="javascript:window.open(this.href, '','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=450,width=650');return false;">
                                        <i class="fa fa-linkedin"></i>
                                    </a>
                                </li>
                                <li>
                                    <a class="envelope-bg"
                                        target="_blank" 
                                        title="Envoyer par mail" 
                                        href="mailto:?subject=<?php urlencode($obj['title']) ?>&body=<?php urlencode($obj['url']) ?>" 
                                        rel="nofollow">
                                        <i class="fa fa-envelope-o"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- END COLUMN 3 -->
                    </div>
                </div>
            </div>
            <!-- COPYRIGHT -->
            <div class="text-center copyright">
                &copy;2015 adlb. All Rights Reserved.
            </div>
            <!-- END COPYRIGHT -->
        </footer>
        <!-- END FOOTER -->
    

    