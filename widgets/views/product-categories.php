<?php

/* @var $this \yii\web\View */
/* @var $categories array */
?>

<?php if(count($categories)): ?>
    <div class="category-plate">
        <?php foreach ($categories as $category): ?>
            <span style="
                    background-color: <?= $category['color'] ?>;
                    width: <?= 100 / count($categories) ?>%;
                    "><?= $category['name'] ?></span>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    Нет категории
<?php endif; ?>
