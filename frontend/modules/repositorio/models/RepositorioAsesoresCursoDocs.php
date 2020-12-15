<?php

namespace frontend\modules\repositorio\models;
use common\models\masters\Documentos;
use common\models\masters\AsesoresCurso;
use common\behaviors\FileBehavior;
use Yii;

/**
 * This is the model class for table "{{%repositorio_asesores_curso_docs}}".
 *
 * @property int $id
 * @property int $asesores_curso_id
 * @property string|null $codocu
 * @property string|null $fpresentacion
 * @property string|null $orcid
 * @property string|null $comentarios
 *
 * @property Documentos $codocu0
 * @property AsesoresCurso $asesoresCurso
 */
class RepositorioAsesoresCursoDocs extends \common\models\base\modelBase
{
    /**
     * {@inheritdoc}
     */
    public $booleanFields=['activo'];
    
    public static function tableName()
    {
        return '{{%repositorio_asesores_curso_docs}}';
    }
    
     public function behaviors() {
        return [
            
            'fileBehavior' => [
                'class' => FileBehavior::className()
            ],
           
        ];
      }
    
    

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['asesores_curso_id'], 'required'],
            [['asesores_curso_id'], 'integer'],
            [['comentarios'], 'string'],
            [['activo'], 'safe'],
            [['codocu'], 'string', 'max' => 3],
            [['fpresentacion'], 'string', 'max' => 10],
            [['orcid'], 'string', 'max' => 250],
            [['codocu'], 'exist', 'skipOnError' => true, 'targetClass' => Documentos::className(), 'targetAttribute' => ['codocu' => 'codocu']],
            [['asesores_curso_id'], 'exist', 'skipOnError' => true, 'targetClass' => AsesoresCurso::className(), 'targetAttribute' => ['asesores_curso_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('base_labels', 'ID'),
            'asesores_curso_id' => Yii::t('base_labels', 'Asesores Curso ID'),
            'codocu' => Yii::t('base_labels', 'Codocu'),
            'fpresentacion' => Yii::t('base_labels', 'Fpresentacion'),
            'orcid' => Yii::t('base_labels', 'Orcid'),
            'comentarios' => Yii::t('base_labels', 'Comentarios'),
        ];
    }

    /**
     * Gets query for [[Codocu0]].
     *
     * @return \yii\db\ActiveQuery|DocumentosQuery
     */
    public function getDocumento()
    {
        return $this->hasOne(Documentos::className(), ['codocu' => 'codocu']);
    }

    /**
     * Gets query for [[AsesoresCurso]].
     *
     * @return \yii\db\ActiveQuery|AsesoresCursoQuery
     */
    public function getAsesoresCurso()
    {
        return $this->hasOne(AsesoresCurso::className(), ['id' => 'asesores_curso_id']);
    }

    /**
     * {@inheritdoc}
     * @return RepositorioAsesoresCursoDocsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RepositorioAsesoresCursoDocsQuery(get_called_class());
    }
}