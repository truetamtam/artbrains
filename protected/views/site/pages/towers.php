<?php
/* @var $this SiteController */
$this->layout = 'plg';
$this->pageTitle='Ханойская башня';
Yii::app()->clientScript->registerMetaTag('Знаменитая игра - Ханойская башня.
Подойдет для подогрева интереса детей к программированию.', 'description', null,
    array(
        'lang' => Yii::app()->language
    )
);

Yii::app()->clientScript->scriptMap = array(
    '*.js' => false,
    '*.css' => false,
);
?>

    <style>
        body {
            font-family: Verdana, serif;
            font-size: 14px;
            color: #393939;
        }

        #wrap {
            width: 861px;
            margin: 15% auto 0 auto;
        }

        #field {
            margin-top: 10%;
            height: 153px;
            overflow: visible;
            border-bottom: 20px solid red;

            border-radius: 2px;
            -moz-border-radius: 2px;
            -webkit-border-radius: 2px;

            -webkit-user-select: none;  /* Chrome all / Safari all */
            -moz-user-select: none;     /* Firefox all */
            -ms-user-select: none;      /* IE 10+ */
            -o-user-select: none;
            user-select: none;
        }

        .tower {
            height: 100%;
            width: 33%;
            float: left;
            position: relative;
        }

        .spike {
            display: block;
            position: absolute;
            left: 47%;
            bottom: 0;
            height: 120%;
            width: 20px;
            background-color: #81b81b;

            border-radius: 2px 2px 0 0;
            -moz-border-radius: 2px 2px 0 0;
            -webkit-border-radius: 2px 2px 0 0;
        }

        .piece {
            background-color: orange;
            position: absolute;
            z-index: 1;
            border-bottom: 1px solid #ee9a00;
            border-top: 1px solid #fed940;

            border-radius: 3px;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
        }

        #piecesSelectNum {
            position: relative;
        }

        #panelBottom {
            padding-top: 30px;
            position: relative;
        }

        #moveCounter {
            position: absolute;
            right: 0;
            top: 0;
            color: #fff;
            background-color: rgb(69, 69, 69);
            font-size: 12px;
            padding: 5px;

            border-radius: 5px;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
        }

    </style>

    <div id="wrap">

        <h1>Ханойская башня</h1>

        <p>Правила:<br>
            1. Переместить все части в третью башню.<br>
            <span id="ruleTwo">2. Большое кольцо нельзя класть на меньшее.</span><br>
            3. За ход, можно брать, одно кольцо башни.
        </p>

        <div id="field">
            <div class="tower" id="t1" num=1>
                <span class="spike"></span>
            </div>
            <div class="tower" id="t2" num=2>
                <span class="spike"></span>
            </div>
            <div class="tower" id="t3" num=3>
                <span class="spike"></span>
            </div>
        </div>

        <div id="panelBottom">

            <label id="piecesSelectNum">Кол-во колец:
                <select id="piecesNum" onchange="hanoy.restart()">
                    <option selected="selected">3</option>
                    <option>4</option>
                    <option>5</option>
                    <option>6</option>
                    <option>7</option>
                    <option>8</option>
                </select>
            </label>

            <p id="moveCounter">Ход: <span></span></p>

            <button onclick="hanoy.restart()">Начать заново</button>

            <div id="messages"></div>

        </div>

    </div>


    <script type="text/javascript">

        /*
         * Hanoy Tower Game
         * author: Roman Ulashev
         */
        (function (hanoy, $) {

            var gameId = '';
            var elemHeight = 51;
            var borders = 2;
            var piecesClass = 'piece';
            var moveCounter = 0;
            var moveLog = '';
            var tmpFilesPath = '../../../../docs/tmp/';
            var piecesNum;

            var $counter = $('#moveCounter').find('span:first');
            var $messages = $('#messages');
            var $piecesNum = $('#piecesNum');

            // 3 towers
            var $tower1 = $('#t1');
            var $tower2 = $('#t2');
            var $tower3 = $('#t3');


            hanoy.restart = function() {

                gameId = '';
                moveCounter = 0;
                moveLog = '';
                $('.tower > .piece').remove();
                $messages.html('');
                $('body').attr('style', ''); // cursor move stays. TODO
                init();
            };


            hanoy.run = function() {
                init();
            };


            function init() {
                gameId = randomStr();
                moveLog = 'Game ID: ' + gameId + '\r\n';
                piecesNum = parseInt($piecesNum.val());

                var draggableOpts = {
                    containment: '#field',
                    revert: 'invalid',
                    revertDuration: 500,
                    scroll: false,
                    cursor: 'move',
                    disabled: true
                };

                createPieces(piecesNum, 0.3, 1, {}, $tower1);
                $tower1.find('.piece').draggable(draggableOpts);
                $tower1.find('.piece:first').draggable('option', {disabled: false});

                $('.tower').droppable({
                    drop: makeMove // prepend > calculate height
                });

                updateMoveCounter(moveCounter);
            }


            // Player makes move
            // uses native droppable.drop:
            function makeMove(event, ui) {

                var $towerDrop = $(this);
                var $dragPiece = ui.helper;
                var $towerPrev = $dragPiece.parent();

                var pieceTop = ui.helper.css('top');

                // if size compared to rules = true, check target pieces
                // otherwise, piece is returned, parent tower pieces evaluated
                if(checkPieceSizes($towerDrop, $dragPiece)) {
                    $dragPiece.prependTo($towerDrop);

                    updateMoveCounter(moveCounter += 1);
                    checkTowerPieces($towerDrop);
                    checkTowerPieces($towerPrev);

                    moveLog += '#' + moveCounter + ': ' + $towerPrev.attr('num') + ' -> ' + $towerDrop.attr('num') +
                            '\r\n';
                    console.log(moveLog); //

                    // counting elems in tower, calcule heights
                    pieceTop = checkPieceTop($towerDrop, $dragPiece);
                } else {
                    pieceTop = checkPieceTop($dragPiece.parent(), $dragPiece);
                }

                // return piece if rejected
                $dragPiece.css('left', $towerDrop.width()/2 - $dragPiece.width()/2);
                $dragPiece.animate({
                    top: pieceTop
                }, 200);

                // trigger victory!
                if($tower3.find('.piece').length === piecesNum) {
                    $('.piece').draggable({disabled: true});
                    $.ajax({
                        type: 'POST',
                        name: 'hanoy',
                        url: 'http://'+ window.location.hostname.toString() +'/site/hanoy',
                        dataType: 'text',
                        data: {moveLog: moveLog, moveLogId: gameId}
                    }).success(function(data) {
                            triggerMessage($messages, '<p>Ура! Вы победили! за {' + moveCounter + '} ходов. ' +
                            '(' + '<a type="text/plain" href="'+ tmpFilesPath + data +'">' +
                                    'история ходов</a>' + ')</p>');
                    });
                }
            }


            function triggerMessage($selector, message) {

                $selector.addClass('success').html(message);
            }

            function rulesReminder($selector, speed) {

                $selector.animate({
                    backgroundColor: '#FFA500'
                }, 'fast', function() {
                    $(this).animate({
                        'backgroundColor': 'transparent'
                    }, speed)
                }).stop(true, true);
            }

            // percMin pieceMax 0.3 ~ 1
            function createPieces(piecesNum, percMin, percMax, paramsObj, $tower) {

                var towerW = $tower.width();
                var towerH = $tower.height();

                var pieceWidthStep = (towerW * percMax - towerW * percMin) / piecesNum;
                var pieceHeight = (towerH / piecesNum) - borders;

                // cycle init
                var pieceWidth = towerW;
                var posTop = towerH - pieceHeight - borders;
                var posLeft = 0;
                var pieceSize = piecesNum;

                // 0 > o
                for(var i = 0; i < piecesNum; i++) {

                    $('<div/>').attr({pieceSize: pieceSize}).css({
                        top: posTop,
                        left: posLeft,
                        width: pieceWidth,
                        height: pieceHeight
                    }).addClass(piecesClass).prependTo($tower);

                    pieceSize = pieceSize -= 1;
                    pieceWidth -= pieceWidthStep;
                    posTop -= pieceHeight;
                    posLeft = towerW/2 - pieceWidth/2;
                }
            }


            function updateMoveCounter(moveCounter) {
                $counter.html(moveCounter);
            }


            function checkPieceTop($tower, $dragPiece) {
                var towerHeight = $tower.height();
                var countPieces = $tower.find('div').length;
                var pieceHeigth = $dragPiece.height();

                if(countPieces === 0) {
                    return towerHeight + pieceHeigth + borders;
                }
                return towerHeight - (countPieces * pieceHeigth + borders);
            }


            // checking tower where piece was dropped
            // set all draggable to disabled:true and first to disabled: false
            function checkTowerPieces($tower) {

                var $pieces = $tower.find('.piece');
                if($pieces.length > 0) {
                    $pieces.draggable('option', {disabled: true});
                    $pieces.first().draggable('option', {disabled: false});
                }
            }


            function checkPieceSizes($dropTower, $dragPiece) {
                var $towerPiece = $dropTower.find('div:first');
                var compared = $towerPiece.attr('pieceSize') > $dragPiece.attr('pieceSize');

                if(!$dragPiece.parent().is($dropTower) && !compared && $towerPiece.length != 0) {
                    rulesReminder($('#ruleTwo'), 3000);
                }

                return compared || $towerPiece.length === 0;
            }


            function randomStr() {
                return new Date().getTime();
            }

        })(window.hanoy = window.hanoy || {}, jQuery);

        hanoy.run();

    </script>