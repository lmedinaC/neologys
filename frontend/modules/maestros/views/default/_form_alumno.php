<?php
    use common\widgets\cbodepwidget\cboDepWidget as ComboDep;  
    use common\models\masters\Personas;
    use common\helpers\ComboHelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use frontend\modules\maestros\MaestrosModule as m;
    use common\helpers\h;
    use kartik\date\DatePicker;
    use common\models\masters\Ubigeos;
?>

<div class="box box-success">
    <?php 
        $paisActual = $model->currentPais(true);
        $paisResidencia = $model->studentPais($model->universidad_id);
        $modelPersona->paisresidencia = $paisResidencia;
        $form = ActiveForm::begin
                (
                    [
                        'id' => 'alumnos-form',
                        'enableAjaxValidation' => true,
                        'fieldClass' => 'common\components\MyActiveField',
                    ]
                ); 
    ?>
    
    <div class="box-header">
        <div class="col-md-12">
            <div class="form-group no-margin">
                <h4><?= Html::encode($paisActual.$paisResidencia) ?></h4>
                <?= Html::submitButton(m::t('verbs', 'Save'), ['class' => 'btn btn-success']) ?>
                <?=($model->isNewRecord)?'':common\widgets\auditwidget\auditWidget::widget(['model'=>$model])?>       
            </div>
        </div>
    </div>    
    
    <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">          
        <?= ComboDep::widget
            (
                [
                    'model'=>$model,               
                    'form'=>$form,
                    'data'=> ComboHelper::getCboUniversidades(),
                    'campo'=>'universidad_id',
                    'idcombodep'=>'alumnos-facultad_id',               
                    'source'=>[\common\models\masters\Facultades::className()=>
                                [
                                    'campoclave'=>'id' , //columna clave del modelo ; se almacena en el value del option del select 
                                    'camporef'=>'desfac',//columna a mostrar 
                                    'campofiltro'=>'universidad_id'  
                                ]
                              ],
                ]
           )
        ?>
    </div>
        
    <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">          
        <?= ComboDep::widget
            (
                [
                    'model'=>$model,               
                    'form'=>$form,
                    'data'=> ($model->isNewRecord)?[]:ComboHelper::getCboFacultades($model->universidad_id),
                    'campo'=>'facultad_id',
                    'idcombodep'=>'alumnos-carrera_id',               
                    'source'=>[\common\models\masters\Carreras::className()=>
                                [
                                    'campoclave'=>'id' , //columna clave del modelo ; se almacena en el value del option del select 
                                    'camporef'=>'nombre',//columna a mostrar 
                                    'campofiltro'=>'facultad_id'  
                                ]
                              ],
                ]
           )
        ?>
    </div>
        
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">    
        <?= $form->field($model, 'carrera_id')->
                         dropDownList(($model->isNewRecord)?[]:ComboHelper::getCboCarreras($model->facultad_id),
                                                            ['prompt'=>'--'.m::t('verbs','Choose a value')."--",]
                                     )
        ?>
    </div>

    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <?= $form->field($model, 'codalu')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">    
        <?= $form->field($model, 'ap')->textInput() ?>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <?= $form->field($model, 'am')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <?= $form->field($model, 'nombres')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <?=$form->field($model, 'tipodoc')->
                  dropDownList(Personas::comboDataField('tipodoc'),['prompt'=>'--'.m::t('verbs','Choose a Value')."--",])
        ?>
    </div>

    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <?= $form->field($model, 'numerodoc')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
        <?= $form->field($model, 'mail')->textInput(['maxlength' => true]) ?>
    </div>
        
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">            
        <?= $form->field($modelPersona, 'cumple',['enableAjaxValidation'=>true])->widget
                   (    
                        DatePicker::class, 
                        [
                            'language' => h::app()->language,
                            'pluginOptions'=>
                            [
                                'format' => h::gsetting('timeUser', 'date')  , 
                                'changeMonth'=>true,
                                'changeYear'=>true,
                                'yearRange'=>"-99:+0",
                            ],
                            'options'=>['class'=>'form-control']
                        ]
                   )
        ?>
    </div>        

    <div class="col-lg-2 col-md-2 col-sm-2   col-xs-12">    
        <?= $form->field($modelPersona, 'sexo')->
                   dropDownList(ComboHelper::getCboSex(),['prompt'=>'--'.m::t('verbs','Choose a Value')."--",])
        ?>
    </div>
        
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">    
        <?= $form->field($modelPersona, 'estcivil')->
                   dropDownList(ComboHelper::getCboEstCivil(),
                               ['prompt'=>'--'.m::t('verbs','Choose a Value')."--",])
        ?>
    </div>

    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <?= $form->field($modelPersona, 'telfijo')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <?= $form->field($modelPersona, 'telmoviles')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <?= $form->field($modelPersona, 'alergias')->textInput(['maxlength' => true]) ?>
    </div>
    
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <?= $form->field($modelPersona, 'gruposangu')->textInput(['maxlength' => true]) ?>
    </div>
    
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <?= $form->field($modelPersona, 'usoregulmedic')->textInput(['maxlength' => true]) ?>
    </div>
    
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <?= $form->field($model, 'motivo')->textInput(['maxlength' => true]) ?>
    </div>
    
    
    
     <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <?=$form->field($modelPersona, 'paisresidencia')->
                  dropDownList(ComboHelper::getCboPaises(),['prompt'=>'--'.m::t('verbs','Choose a Value')."--",''])
        ?>
    </div>     
    
    <?php
        if ($modelPersona->paisresidencia=='PE')    
        {                
    ?>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"> 
        <?= ComboDep::widget(
            [
                'model'=>$modelPersona,               
                'form'=>$form,
                'data'=> ComboHelper::getCboDepartamentos(),
                'campo'=>'depdir',
                'idcombodep'=>'personas-provdir',
                'source'=>
                [   
                    Ubigeos::className()=>
                    [
                        'campoclave'=>'codprov' , //columna clave del modelo ; se almacena en el value del option del select 
                        'camporef'=>'provincia',//columna a mostrar 
                        'campofiltro'=>'coddepa'  
                    ]
                ],
            ])
        ?>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"> 
        <?= ComboDep::widget(
            [
                'model'=>$modelPersona,               
                'form'=>$form,
                'data'=> ($modelPersona->isNewRecord)?[]:ComboHelper::getCboProvincias($modelPersona->depnac),
                'campo'=>'provdir',
                'idcombodep'=>'personas-distdir',
                'source'=>
                [
                    Ubigeos::className()=>
                    [
                        'campoclave'=>'coddist' , //columna clave del modelo ; se almacena en el value del option del select 
                        'camporef'=>'distrito',//columna a mostrar 
                        'campofiltro'=>'codprov'  
                    ]
                ],
            ])
        ?>
    </div> 
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">    
        <?= $form->field($modelPersona, 'distdir')->
                   dropDownList(($modelPersona->isNewRecord)?[]:ComboHelper::getCboDistritos($modelPersona->provnac),
                                ['prompt'=>'--'.m::t('verbs','Choose a Value')."--",])
        ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">    
        <?= $form->field($modelPersona, 'domicilio')->textInput(['maxlength' => true]) ?>
    </div>
     <?php  
        }
        else
        {
    ?>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">    
        <?= $form->field($modelPersona, 'lugarresidencia')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">    
        <?= $form->field($modelPersona, 'domicilio')->textInput(['maxlength' => true]) ?>
    </div>
    <?php   
        }
    ?>
    
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">    
        <?= $form->field($modelPersona, 'codcontpaisresid')->textInput(['maxlength' => true]) ?>
    </div>
    
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">    
        <?= $form->field($modelPersona, 'parentcontpaisresid')->textInput(['maxlength' => true]) ?>
    </div>
    
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">    
        <?= $form->field($modelPersona, 'telfijo')->textInput(['maxlength' => true]) ?>
    </div>
    
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <?=$form->field($modelPersona, 'pais')->
                  dropDownList(ComboHelper::getCboPaises(),['prompt'=>'--'.m::t('verbs','Choose a Value')."--",''])
        ?>
    </div>        

    <?php
        if ($modelPersona->pais=='PE')
        {
    ?>

    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"> 
        <?= ComboDep::widget(
            [
                'model'=>$modelPersona,               
                'form'=>$form,
                'data'=> ComboHelper::getCboDepartamentos(),
                'campo'=>'depnac',
                'idcombodep'=>'personas-provnac',
                'source'=>
                [   
                    Ubigeos::className()=>
                    [
                        'campoclave'=>'codprov' , //columna clave del modelo ; se almacena en el value del option del select 
                        'camporef'=>'provincia',//columna a mostrar 
                        'campofiltro'=>'coddepa'  
                    ]
                ],
            ])
        ?>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"> 
        <?= ComboDep::widget(
            [
                'model'=>$modelPersona,               
                'form'=>$form,
                'data'=> ($modelPersona->isNewRecord)?[]:ComboHelper::getCboProvincias($modelPersona->depnac),
                'campo'=>'provnac',
                'idcombodep'=>'personas-distnac',
                'source'=>
                [
                    Ubigeos::className()=>
                    [
                        'campoclave'=>'coddist' , //columna clave del modelo ; se almacena en el value del option del select 
                        'camporef'=>'distrito',//columna a mostrar 
                        'campofiltro'=>'codprov'  
                    ]
                ],
            ])
        ?>
    </div> 
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">    
        <?= $form->field($modelPersona, 'distnac')->
                   dropDownList(($modelPersona->isNewRecord)?[]:ComboHelper::getCboDistritos($modelPersona->provnac),
                                ['prompt'=>'--'.m::t('verbs','Choose a Value')."--",])
        ?>
    </div>
    <?php   
        }
        else
        {
    ?>        
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">    
        <?= $form->field($modelPersona, 'lugarnacimiento')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">    
        <?= $form->field($modelPersona, 'domiciliopaisorigen')->textInput(['maxlength' => true]) ?>
    </div>
    <?php   
        }
    ?>
    
    
    
    
    
        
          
       
        
        
    
    <?php ActiveForm::end(); ?>
</div>
