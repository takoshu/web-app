<?php

namespace partials;

use lib\Auth;

function topic_header_item($topic, $from_top_page)
{
?>
    <div class="row">
        <div class="col">
            <!-- 左側 -->
            <?php chart($topic); ?>
        </div>
        <div class="col my-5">
            <!-- 右側 -->
            <?php topic_main($topic, $from_top_page); ?>
            <?php comment_form($topic); ?>
        </div>
    </div>
<?php
}

function chart($topic)
{
?>
    <canvas id="myPieChart" data-laugh="<?php echo $topic->laugh; ?>" data-touch="<?php echo $topic->touch; ?>" data-disgust="<?php echo $topic->disgust; ?>" data-meet="<?php echo $topic->meet; ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
    <script>
    const $ctx = document.getElementById("myPieChart");
    const laugh = $ctx.dataset.laugh;
    const touch = $ctx.dataset.touch;
    const disgust = $ctx.dataset.disgust;
    const meet = $ctx.dataset.meet;
    const myPieChart = new Chart($ctx, {
    type: 'pie', // 円グラフを使用
    data: {
        labels: ["a", "b", "c", "d"],
        datasets: [{
            backgroundColor: [
             "#BB5179",
             "#FAFF67",
             "#58A27C",
             "#3C00FF"
            ],
            data: [laugh, touch, disgust, meet]
        }]
    },
    options: {
    }
 });
 </script>
    <?php
}

function topic_main($topic, $from_top_page)
{
?>
    <div>
         <?php if ($topic !== null): ?>
            <?php if ($from_top_page) :  ?>
                <h1 class="sr-only">みんなのアンケート</h1>
                <h2 class="h1">
                    <a class="text-body" href="<?php the_url('topic/detail?topic_id=' . $topic->id); ?>">
                        <?php echo $topic->title; ?>
                    </a>
                </h2>
            <?php else : ?>
                <h1><?php echo $topic->title; ?></h1>
            <?php endif; ?>
            <span class="mr-1 h5">Posted by <?php echo $topic->nickname; ?></span>
            <span class="mr-1 h5">&bull;</span>
            <span class="h5"><?php echo $topic->views; ?> views</span>
        <?php else: ?>
            <h1>トピックが見つかりません</h1>
        <?php endif; ?>
    </div>
    <div class="container text-center my-4">
        <div class="row justify-content-around">
        <?php if ($topic !== null): ?>
            <div class="likes-green col-auto">
                <div class="display-1"><?php echo $topic->likes; ?></div>
                <div class="h4 mb-0">異議なし</div>
            </div>
            <div class="dislikes-red col-auto">
                <div class="display-1"><?php echo $topic->dislikes; ?></div>
                <div class="h4 mb-0">異議あり</div>
            </div>
        <?php else: ?>
            <div class="h4">トピックが見つかりません</div>
        <?php endif; ?>
        </div>
    </div>
<?php
}

function comment_form($topic)
{
?>
    <?php if (Auth::isLogin()) : ?>
        <?php if (!Auth::seeNowUserPost($topic)): ?>
            <form action="<?php the_url('topic/detail'); ?>" method="POST" novalidate autocomplete="off">
                <span class="h4">コメント</span>
                <?php if ($topic !== null): ?>
                <input type="hidden" name="topic_id" value="<?php echo $topic->id; ?>">
                <div class="form-group">
                    <textarea class="w-100 border-light" name="body" id="body" rows="5" maxlength="100"></textarea>
                </div>
                <div class="container">
                    <div class="row h4 form-group">
                        <div class="col-auto d-flex align-items-center pl-0">
                            <div class="form-check-inline">
                                <input class="form-check-input" type="radio" id="agree" name="agree" value="1" checked>
                                <label for="agree" class="form-check-label">異議なし</label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input" type="radio" id="disagree" name="agree" value="0">
                                <label for="disagree" class="form-check-label">異議あり</label>
                            </div>
                        </div>
                        <input type="submit" value="送信" class="col btn btn-success shadow-sm">
                    </div>
                </div>
        <?php else: ?>
            <div class="text-center">トピックが見つかりません</div>
        <?php endif; ?>
        <?php else: ?>
                <div class="text-center">あなたの投稿です</div>
            <?php endif; ?>
        </form>
    <?php else : ?>
        <div class="text-center mt-5">
            <div class="mb-2">ログインしてアンケートに参加しよう！</div>
            <a href="<?php the_url('login'); ?>" class="btn btn-lg btn-success">ログインはこちら！</a>
        </div>
    <?php endif; ?>
<?php
}