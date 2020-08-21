<?php
use yii\helpers\Html;
use frontend\modules\maestros\MaestrosModule as m;

$this->title = m::t('labels', 'Create Student');
$this->params['breadcrumbs'][] = ['label' => m::t('labels', 'Students'), 'url' => ['index-alumnos']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="documentos-create">
    <h4><?= Html::encode($this->title) ?></h4>
    <div class="box box-success">    
        <?= $this->render('_form_alumno', ['model' => $model,]) ?>
    </div>
</div>
