<!-- страница карточек с играми-->

<div class="game <?php if($game["sport_type_id"] == 1){ echo 'basketball 1'; }elseif($game["sport_type_id"] == 2){ echo 'football';} ?>"  data-id-game="<?= $game['id']; ?>">
    <div class="gameTop">
        <span class="number"><?= $i ?>.</span>
        <span class="time"><?php 
                    if(date_format(date_create($game['time']), 'd') == (date("d")+1))
                        echo 'Завтра, '.date_format(date_create($game['time']), 'H:i');
                    elseif(date_format(date_create($game['time']), 'd') == (date("d")))
                        echo 'Сегодня, '.date_format(date_create($game['time']), 'H:i');
                    else
                        echo date_format(date_create($game['time']), 'd-m H:i');
        ?></span>
        <div class="social">
            <a href="#"><i class="fa fa-vk" aria-hidden="true"></i></a>
            <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
        </div>
    </div>
    <div class="people">
        <p>Игроков:<span class="count"><?= $game['count'] ?></span></p>
        <div class="scroll">
            <div class="right"></div>
            <div class="circle">
                <div class="plus man"  data-id-game-plus="<?= $game['id']; ?>" onclick="people(<?= $game['id']; ?>,'<?= $game['plus'] ?>')"><span><?= $game['plus'] ?></span></div>
                <?php
                    if(Yii::$app->user->identity)
                        $userAuth = Yii::$app->user->identity->getId();
                    else
                        $userAuth = 0;
                    foreach ($users[$i-1] as $j => $user) {
                        if($userAuth == $user['user_id'])
                            $link = '/profile';
                        else
                            $link = '/users/'.$user['user_id'];
                        echo '<a href="'.$link.'" target="_blank"><img src="/img/uploads/'.$user['picture'].'" class="man"></a>';
                        $j++;
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="bottom">
        <div class="gameType">Игра:<span><?= $game['sport'] ?></span></div>
        <div class="ballType">Мяч:<span><?php if($game['need_ball'] == 1) echo 'есть'; else echo 'нет'; ?></span></div>
    </div>
</div>