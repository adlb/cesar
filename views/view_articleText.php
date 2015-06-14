<?php echo $obj['article']['textContent']; ?>

<?php foreach($obj['article']['subArticles'] as $article)
    renderPartial('newsText', $article);
?>
