<?php

namespace frontend\modules\acad\models;

/**
 * This is the ActiveQuery class for [[AcadSyllabusDocentes]].
 *
 * @see AcadSyllabusDocentes
 */
class AcadSyllabusDocentesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return AcadSyllabusDocentes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AcadSyllabusDocentes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
