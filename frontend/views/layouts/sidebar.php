<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href=<?= \yii\helpers\Url::home() ?> class="brand-link">
        <img src="<?= Yii::getAlias('@web') . '/csu.png' ?>" alt="CSU Logo" class="brand-image img-circle"
             style="opacity: .8; max-height: 40px;">
        <span class="brand-text font-weight-light"><?= Yii::$app->name ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <!--<div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $assetDir ?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>-->

        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    ['label' => 'Главная', 'iconClass' => 'logo nav-icon', 'url' => \yii\helpers\Url::home()],
                    [
                        'label' => 'Контингент',
                        'icon' => 'id-card',
                        'items' => [
                            ['label' => 'Потоки', 'icon' => 'paper-plane', 'url' => ['/flow/index']],
                            ['label' => 'Группы', 'icon' => 'users', 'url' => ['/group/index']],
                            ['label' => 'Студенты', 'icon' => 'user-graduate', 'url' => ['/student/index']],
                            ['label' => 'Академические степени', 'icon' => 'graduation-cap', 'url' => ['/group/academic-degree']],
                            ['label' => 'Направления', 'icon' => 'clipboard', 'url' => ['/group/direction']],
                        ]
                    ],
                    [
                        'label' => 'Приказы и справки',
                        'icon' => 'file',
                        'items' => [
                            ['label' => 'Приказы', 'icon' => 'folder-open', 'url' => ['#']],
                            ['label' => 'Справки', 'icon' => 'stamp', 'url' => ['#']],
                            ['label' => 'Шаблоны', 'icon' => 'clipboard-check', 'url' => ['/template/index']],
                        ]
                    ]
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>