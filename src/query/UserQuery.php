<?php

namespace app\query;

/**
 * This is the ActiveQuery class for [[\app\models\User]].
 * @see \app\models\User
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @param string $phone
     * @return $this
     */
    public function byPhone(string $phone): self
    {
        return $this->andWhere(['phone' => $phone]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
