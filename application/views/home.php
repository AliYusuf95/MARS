<style>
    section#counters{
        padding: 40px 15px 40px 15px;
    }
    ul.counters-list .counter{
        font-size: 2em;
        font-weight: 600;
    }
    ul.counters-list li .counter-item{
        border: 1px solid #0275d8;
        color: #0275d8;
        height: 150px;
        width: 150px;
        background-color: transparent;
        font-weight: 400;
        -webkit-transition: all .3s ease-in-out;
        -moz-transition: all .3s ease-in-out;
        transition: all .3s ease-in-out;
        -moz-border-radius: 75px;
        -webkit-border-radius: 75px;
        border-radius: 75px;
        margin: 0;
        display: table-cell;
        justify-content: center;
        align-items: center;
        padding: 10px 16px;
        font-size: 18px;
        line-height: 1.3333333;
        text-align: center;
        vertical-align: middle;
        cursor: default;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
    }
    ul.counters-list{
        margin-top:0
    }
    ul.counters-list li{
        padding-right: 25px;
        padding-left: 25px;
    }
    @media (max-width:1199px){
        ul.counters-list{
            margin-top:15px
        }
    }
    @media (max-width:767px){
        ul.counters-list li{
            display:block;
            margin-bottom:40px;
            padding:0;
        }
        ul.counters-list div.counter-item{
            display: inline-flex;
        }
        ul.counters-list li:last-child{
            margin-bottom:0
        }
    }
</style>
<div class="container">
    <p>view file: /application/views/home.php</p>
</div>
<?php if(isset($counters)): ?>
<section id="counters" class="container text-center">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <ul class="list-inline counters-list">
                <?php /** @var array() $counters */
                foreach($counters as $counter): ?>
                <li>
                    <div class="counter-item">
                        <p>
                            <span class="counter"><?php echo $counter["counts"]; ?></span>
                            <br/>
                            <span class="network-name"><?php echo $counter["name"]; ?></span>
                        </p>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>
<?php endif; ?>
<script>
    $('.counter').each(function () {
        $(this).prop('Counter',0).animate({
            Counter: $(this).text()
        }, {
            duration: 2000,
            easing: 'swing',
            step: function (now) {
                $(this).text(Math.ceil(now));
            }
        });
    });
</script>