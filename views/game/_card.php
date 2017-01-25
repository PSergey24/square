<!-- страница карточек с играми-->

<div class="col-xs-12 col-lg-6 first"  data-id-game="<?= $listGame['id']; ?>">
    <div class=<?php if($listGame['sport_type_id'] == 1) echo '"shadow box game-new basketball"'; elseif($listGame['sport_type_id'] == 2) echo '"shadow box game-new football"'; else echo '"shadow box game-new"'; ?> >
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="top">
                <div class="square"><?= $nameAreaArr[$i]; ?></div>
                <div class="onmap"><i class="fa fa-globe fa-lg" aria-hidden="true" onclick="setMapCenter(<?= $listGame['id']; ?>)"></i></div>
            </div>
            <div id="maps" class="visible-xs"><!--КАРТА ДЛЯ ТЕЛЕФОНА-->
                                        
            </div>
            <div class="divider"></div>
            <div class="people">
                <p>Игроков: <span class="count"><?= $countUsersArr[$i]; ?></span></p>
                <div class="scroll">
                    <div class="right"></div>
                    <div class="circle">
                        <div class="plus man" data-id-game-plus="<?= $listGame['id']; ?>" onclick="people(<?= $listGame['id']; ?>,'<?= $plusMan[$i]; ?>')"><span><?= $plusMan[$i]; ?></span></div>
                        <?php
                        if(Yii::$app->user->identity)
                            $userAuth = Yii::$app->user->identity->getId();
                        else
                            $userAuth = 0;
                            for($j=0;$j<$countUsersArr[$i];$j++)
                            {
                                if($userAuth == $idUsersArr[$i][$j]['user_id'])
                                    $link = 'profile';
                                else
                                    $link = 'users/'.$idUsersArr[$i][$j]['user_id'];
                                echo '<a href="'.$link.'" target="_blank"><img src="/img/uploads/'.$pictureUsersArr[$i][$j]['picture'].'" class="man"></a>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="description col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="type">
                <span class="small"><i class="fa fa-dribbble" aria-hidden="true"></i>Игра</span><br>
                <span class="big"><?= $nameSportArr[$i]; ?></span>
            </div>
            <div class="time">
                <span class="small"><i class="fa fa-clock-o" aria-hidden="true"></i>Время</span><br>
                <span class="big">
                    <?php 
                    if(date_format(date_create($listGame['time']), 'd') == (date("d")+1))
                        echo 'завтра '.date_format(date_create($listGame['time']), 'H:i');
                    elseif(date_format(date_create($listGame['time']), 'd') == (date("d")))
                        echo 'сегодня '.date_format(date_create($listGame['time']), 'H:i');
                    else
                        echo date_format(date_create($listGame['time']), 'd-m H:i');
                    ?>
                </span>
            </div>
            <div class="ball">
                <span class="small"><i class="fa fa-futbol-o" aria-hidden="true"></i>Мяч</span><br>
                <span class="big"><?php if($listGame['need_ball'] == 1) echo 'есть'; else echo 'нет'; ?></span>
            </div>
        </div>
    </div>
</div>