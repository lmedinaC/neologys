<?php
USE frontend\modules\inter\Module as m;
$persona=$model->persona;
$this->title = m::t('labels', 'File candidate: {name}', [
    'name' => $persona->fullName(),
]);
$this->params['breadcrumbs'][] = ['label' => m::t('labels', 'Candidates'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => m::t('labels', 'Programa'), 'url' => ['update', 'id' => $modelTallerdet->talleres_id]];
$this->params['breadcrumbs'][] = m::t('labels', 'File');
?>
<?php
echo \common\widgets\spinnerWidget\spinnerWidget::widget();
?>
<h4><span class="fa fa-calendar"></span><?='   '.\yii\helpers\Html::encode(yii::t('sta.labels','File')).'-'.$persona->fullName() ?></h4>
<div class="box box-success">
<div class="box body">

<?php  
 use kartik\tabs\TabsX;
?>
     <div class="col-md-12">
    <div class="btn-group">   
          <?php //$url=\yii\Helpers\Url::toRoute(['/sta/alumnos/retira-del-programa','id'=>$modelTallerdet->id]);  ?>      
              <?php //\yii\Helpers\Html::a('<span class="fa fa-file-pdf" ></span>'.'  '.yii::t('sta.labels','Retirar Alumno'),$url,['target'=>'_blank','data-pjax'=>'0','class'=>"btn btn-danger"])?>
            
     </div>            
    </div>
    <br><br>
<?php

 echo $this->render('_alu_basico',
               ['model' =>$model ,'persona'=>$persona,'alumno'=>$alumno
                ]);

?>

<?php 

 echo TabsX::widget([
     'position' => TabsX::POS_ABOVE,
     'bordered'=>true,
    'align' => TabsX::ALIGN_LEFT,
      'encodeLabels'=>false,
    'items' => [
        [
            'label' =>'<i class="glyphicon glyphicon-hourglass"></i> '.yii::t('base.names','Citas'), //$this->context->countDetail() obtiene el contador del detalle
            'content'=> $this->render('view_tab_files',['model' =>$model ,'persona'=>$persona,'alumno'=>$alumno]),
            'active' => true,
             'options' => ['id' => 'tnID3'],
        ],
        [
            'label' =>'<i class="glyphicon glyphicon-calendar"></i> '. yii::t('base.names','Programación'), //$this->context->countDetail() obtiene el contador del detalle
            'content'=> $this->render('view_tab_calendar',['model' =>$model ,'persona'=>$persona,'alumno'=>$alumno]),
            'active' => false,
             'options' => ['id' => 'myy6nID4'],
        ],
      [
            'label' =>'<i class="glyphicon glyphicon-folder-open"></i>   '. yii::t('base.names','Documentos'), //$this->context->countDetail() obtiene el contador del detalle
            'content'=> $this->render('view_tab_entrevistas',['model' =>$model ,'persona'=>$persona,'alumno'=>$alumno]),
            'active' => false,
             'options' => ['id' => 'mxx4ID4'],
        ],
    ],
]); 
    ?> 
</div>
    </div>
