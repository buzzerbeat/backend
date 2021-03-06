<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => '基础', 'options' => ['class' => 'header']],
                    ['label' => '用户管理', 'icon' => 'fa fa-user', 'url' => '/admin-user'],
                    ['label' => '图片管理', 'icon' => 'fa fa-picture-o', 'url' => '/image-admin/list'],
                    ['label' => '视频管理', 'icon' => 'fa fa-video-camera', 'url' => '/video-admin'],
                    ['label' => '任务管理', 'icon' => 'fa fa-tasks', 'url' => '/crawl-task-admin/index?sort=-id'],


                    ['label' => '新闻', 'options' => ['class' => 'header']],
                    ['label' => '新闻管理', 'icon' => 'fa fa-newspaper-o', 'url' => '/article/admin-article'],

                    ['label' => '壁纸', 'options' => ['class' => 'header']],
                    ['label' => '分类管理', 'icon' => 'fa fa-list', 'url' => '/wallpaper/album-admin'],
                    ['label' => '图片管理', 'icon' => 'fa fa-picture-o', 'url' => '/wallpaper/image-admin'],

                    ['label' => '小视频', 'options' => ['class' => 'header']],
                    ['label' => '视频管理', 'icon' => 'fa fa-video-camera', 'url' => '/microvideo/mv-video-admin/list'],
                    ['label' => 'Tag管理', 'icon' => 'fa fa-tag', 'url' => '/microvideo/mv-video-admin/tag'],
                    ['label' => '关键词', 'icon' => 'fa fa-key', 'url' => '/microvideo/mv-video-admin/keyword'],
                    ['label' => '评论', 'icon' => 'fa fa-comment', 'url' => '/microvideo/mv-comment-admin/list'],

                    ['label' => '任务分发', 'options' => ['class' => 'header']],
                    ['label' => '任务管理', 'icon' => 'fa fa-tasks', 'url' => '/taskdist/task-admin/'],

                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    ['label' => '工具', 'options' => ['class' => 'header']],
                    ['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug']],
                ],
            ]
        ) ?>

    </section>

</aside>
