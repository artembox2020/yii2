<?php

namespace frontend\models;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\data\ArrayDataProvider;
use Yii;

/**
 * This is the model class for table "zlog".
 *
 * @property integer $id
 * @property string $r_date
 * @property string $edate
 * @property string $imei
 * @property string $type
 * @property string $status_dev
 * @property string $ch_uah
 * @property string $ch_map
 * @property string $ch_incasso
 * @property string $col_cup
 * @property string $tarif
 * @property string $num_dev
 * @property string $lmodem
 * @property string $price
 * @property string $col_mon
 * @property string $rezim
 * @property string $tstir
 * @property string $otzim_type
 * @property string $p_stir
 * @property string $polosk
 * @property string $intensiv
 * @property string $sv
 * @property string $nch
 * @property string $col_gel
 * @property string $by_gel
 * @property string $esum
 */
class Zlog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zlog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['r_date'], 'safe'],
            [['edate', 'imei', 'type', 'status_dev', 'ch_uah', 'ch_map', 'ch_incasso', 'col_cup', 'tarif', 'num_dev', 'lmodem', 'price', 'col_mon', 'rezim', 'tstir', 'otzim_type', 'p_stir', 'polosk', 'intensiv', 'sv', 'nch', 'col_gel', 'by_gel'], 'string', 'max' => 250],
            [['esum'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'r_date' => Yii::t('app', 'R Date'),
            'edate' => Yii::t('app', 'Edate'),
            'imei' => Yii::t('app', 'Imei'),
            'type' => Yii::t('app', 'Type'),
            'status_dev' => Yii::t('app', 'Status Dev'),
            'ch_uah' => Yii::t('app', 'Ch Uah'),
            'ch_map' => Yii::t('app', 'Ch Map'),
            'ch_incasso' => Yii::t('app', 'Ch Incasso'),
            'col_cup' => Yii::t('app', 'Col Cup'),
            'tarif' => Yii::t('app', 'Tarif'),
            'num_dev' => Yii::t('app', 'Num Dev'),
            'lmodem' => Yii::t('app', 'Lmodem'),
            'price' => Yii::t('app', 'Price'),
            'col_mon' => Yii::t('app', 'Col Mon'),
            'rezim' => Yii::t('app', 'Rezim'),
            'tstir' => Yii::t('app', 'Tstir'),
            'otzim_type' => Yii::t('app', 'Otzim Type'),
            'p_stir' => Yii::t('app', 'P Stir'),
            'polosk' => Yii::t('app', 'Polosk'),
            'intensiv' => Yii::t('app', 'Intensiv'),
            'sv' => Yii::t('app', 'Sv'),
            'nch' => Yii::t('app', 'Nch'),
            'col_gel' => Yii::t('app', 'Col Gel'),
            'by_gel' => Yii::t('app', 'By Gel'),
            'esum' => Yii::t('app', 'Esum'),
        ];
    }
	
	public function get_all(){
        $crit = new CDbCriteria();
        $crit->order = 'date DESC';
        return $this->findAll($crit);
    }
    
    public function get_sum($imei, $dateFrom, $dateTo)
    {
		$query = Zlog::find()
				->select('*')
				->where("imei = '" . $imei . "'")
				->distinct()
				->andWhere(['between','edate', $dateFrom, $dateTo])
				->all();
        return $query;
    }
    
    public function get_sum_a($dateFrom, $dateTo)
    {
		$query = Zlog::find()
				->select('*')
				->where("1 = 1")
				->distinct()
				->andWhere(['between','edate', $dateFrom, $dateTo])
				->all();
        return $query;
        
    }
    
    public static function get_for_log($imei, $type){
        
        if($imei == ''){
            $condition = "1 = 1";
        } else {
            $condition = "imei LIKE '%" . $imei . "%'";
        }
        if($type == ''){
            $condition.= " AND 1 = 1";
        } else {
            $condition.= " AND type LIKE '%" . $type . "%'";
        }
				
		$query = Zlog::find()->andWhere($condition)->distinct()->orderBy('id');

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
				'pagination' => [ 
					'pageSize' => 20,
				],
		]);

        return $dataProvider;
    }
}
