                <li class="dropdown notifications-menu">
                    <a href="#" id="favoritos_nav" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bookmark"></i>
                        
                    </a>
                   
                </li>
                <?php $cadenaJsd="$('#favoritos_nav').on('click',function(){  $.ajax({"
                        . "url: '".\yii\helpers\Url::toRoute('/site/ajax-add-favorite')."', "
                        ." type:'GET', "
                        ." dataType: 'json', "
                        ." success: function(json) {
                            var n = Noty('id');
                             $.noty.setText(n.options.id, json['success']);
                             $.noty.setType(n.options.id, 'success'); 
                   
                        }  "
                            . " "
                        . "});  })";  ?>
                <?php $this->registerJs($cadenaJsd,$this::POS_END);   ?>