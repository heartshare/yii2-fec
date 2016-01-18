<?php
/*
������Ҫ�����ݿ�Ĳ������ӣ�û�й��ܡ�

*/
namespace fec\helpers;
use Yii; 
#http://blog.csdn.net/terry_water/article/details/50130275
class CDB
{	
	# http://www.yiichina.com/doc/guide/2.0/db-active-record
	# ��������1:���ݱ���ѯ
	/*
	@��һ��ѯ��������һ�����󣬿�ͨ���������ʽ���� $customer['quote_id'];
		������ͨ�����Եķ�ʽ $customer->quote_id;
		$customer = Customer::findOne(['customer_id' => (int)$customer_id]);
	
	@ȫ����ѯ�����ص��Ƕ���
		$customers = Customer::find()
			->where(['status' => Customer::STATUS_ACTIVE])
			->orderBy('id')
			->all();
	@ȫ����ѯ�����ص�������  (��ѯ100-109��)
		$customers = Customer::find()
			->asArray()
			->where(['status' => Customer::STATUS_ACTIVE])
			->orderBy(['id' => SORT_ASC,'name' => SORT_DESC])
			->limit(10)
			->offset(100)
			->all();
	@��ѯ������
		$count = Customer::find()
			->where(['status' => Customer::STATUS_ACTIVE])
			->count();
	@��idΪ�����ķ�ʽ��ѯ
		$customers = Customer::find()->indexBy('id')->all();
		
	@��ԭ����sql��ѯ����ֻ�������һ������
		$sql = 'SELECT * FROM customer';
		$customers = Customer::findBySql($sql)->all();	
		
	@������ȡ���ݣ������ã���Դ����ݣ�
		һ����ȡ 10 ���ͻ���Ϣ
		foreach (Customer::find()->batch(10) as $customers) {
			$customers �� 10 ������ٵĿͻ����������
		}
		һ����ȡ 10 ���ͻ���һ��һ���ر�������
		foreach (Customer::find()->each(10) as $customer) {
			$customer ��һ�� ��Customer�� ����
		}
		̰������ģʽ����������ѯ
		foreach (Customer::find()->with('orders')->each() as $customer) {
		}
	*/
	
	
	# ��������2:���ݱ�����
	/*
	@�����¿ͻ��ļ�¼
		$customer = new Customer();
		$customer->name = 'James';
		$customer->email = 'james@example.com';
		//��ͬ�� $customer->insert();
		$customer->save();  
	
	@ͨ��post����ķ�ʽ��ֵ
		$model = new Customer;
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
		
		}
		####����AR�̳���yii\base\Model��������ͬ��Ҳ֧��Model���������롢��֤�����ԡ����磬���������һ��rules�����������ǵ�yii\base\Model::rules()��ģ���Ҳ���Ը�ARʵ��������ֵ����Ҳ����ͨ������yii\base\Model::validate()ִ��������֤��
		####������� save()��insert()��update() ����������ʱ�����Զ�����yii\base\Model::validate()�����������֤ʧ�ܣ����ݽ����ᱣ������ݿ⡣
	
	*/
	
	# ��������3:���ݱ�����
	/*
	@�������пͻ���¼
		$customer = Customer::findOne(['id' => 1]);
		$customer->email = 'james@example.com';
		//��ͬ�� $customer->update();
		$customer->save();  
		
	@���пͻ���age�����䣩�ֶμ�1��
		Customer::updateAllCounters(['age' => 1]);		
	*/
	
	
	# ��������4�����ݱ�����ɾ��
	/*
	@ɾ�����пͻ���¼
		$customer = Customer::findOne(['id' => 1]);
		$customer->delete();

	@ɾ������������20���Ա�Ϊ�У�Male���Ŀͻ���¼
		Customer::deleteAll('age > :age AND gender = :gender', [':age' => 20, ':gender' => 'M']);
			
			
	*/
	
	# ��������5����ȡĬ��ֵ
	/*
	
		��ı���Ҳ��������Ĭ��ֵ����ʱ���������Ҫ��ʹ��web������ʱ���ARԤ��һЩֵ��
		�������Ҫ����������������ʾ��������ǰͨ������
		loadDefaultValues()������ʵ�֣� 
		php $customer = new Customer();
		$customer->loadDefaultValues(); // ... ��Ⱦ $customer �� HTML ���� ... `
	*/

	
	public static function getDefaultDb(){
		return Yii::$app->db;
	}
	

	#1 .ͨ��sql�鿴���еļ�¼
	# example:  DB::findBySql('select * from sales_order_info where order_id > :order_id'
	#							,[':order_id'=>1 ]);
	public static function findAllBySql($sql,$data=[],$db=''){
		# example: $sql = 'SELECT * FROM  sales_flat_quote';
		if(!$db){
			$db = self::getDefaultDb();
		}
		$result = $db->createCommand($sql,$data)
					->queryAll();
		return $result;
	}
	
	
	
	#2 .ͨ��sql�鿴һ����¼
	# example: DB::findOneBySql('select * from sales_order_info where order_id = :order_id'
	#							,[':order_id'=>1 ]);
	public static function findOneBySql($sql,$data=[],$db=''){
		# example: $sql ='SELECT * FROM post WHERE id=1'
		if(!$db){
			$db = self::getDefaultDb();
		}
		$result = $db->createCommand($sql,$data)
				->queryOne();
		return $result;
	}
	
	#3 .ͨ��sql�����¼
	# $sql 	= "insert into sales_order_info (increment_id) values (:increment_id) ";
	# $data = ['increment_id'=>'eeeeeeeeee'];
	# $dd 	= DB::insertBySql($sql,$data);
	public static function insertBySql($sql,$data=[],$db=''){
		if(!$db){
			$db = self::getDefaultDb();
		}
		$result = $db->createCommand($sql,$data)
				->execute();
		return $result;
	
	}
	
	#4 .ͨ��sql����
	# $sql = "update sales_order_info set increment_id = :iid where increment_id = :increment_id";
	# $data = ['iid'=>'ddd','increment_id'=>'eeeeeeeeee'];
	# $dd = DB::insertBySql($sql,$data);
	public static function updateBySql($sql,$data=[],$db=''){
		if(!$db){
			$db = self::getDefaultDb();
		}
		$result = $db->createCommand($sql,$data)
				->execute();
		return $result;
	
	}
	
	#5. ͨ��sqlɾ��
	# $sql = "delete from sales_order_info  where increment_id = :increment_id";
	# $data = ['increment_id'=>'eeeeeeeeee'];
	# $dd = DB::insertBySql($sql,$data);
	public static function deleteBySql($sql,$data=[],$db=''){
		if(!$db){
			$db = self::getDefaultDb();
		}
		$result = $db->createCommand($sql,$data)
				->execute();
		return $result;
	
	}
	
	#6. �����������ݷ�ʽ
	# $table 		= 'sales_order_info';
	# $columnsArr = ['increment_id','order_status'];
	# $valueArr 	= [
	# 				['Tom', 30],
	# 				['Jane', 20],
	# 				['Linda', 25]
	# 				];
	# DB::batchInsert($table,$columnsArr,$valueArr);
		
	public static function batchInsert($table,$columnsArr,$valueArr,$db=''){
		if(!$db){
			$db = self::getDefaultDb();
		}
		$db->createCommand()
					->batchInsert($table,$columnsArr,$valueArr)
					->execute();
	
	}
	
	
	
	/*
	�������
		# ��ʼ����
		$innerTransaction = Yii::$app->db->beginTransaction();
		try {
			$innerTransaction->commit();
		} catch (Exception $e) {
			$innerTransaction->rollBack();
		}
	
	*/
	
	
	/*
	����Ƕ�ף�
	$outerTransaction = $db->beginTransaction();
	try {
		$db->createCommand($sql1)->execute();

		$innerTransaction = $db->beginTransaction();
		try {
			$db->createCommand($sql2)->execute();
			$innerTransaction->commit();
		} catch (Exception $e) {
			$innerTransaction->rollBack();
		}

		$outerTransaction->commit();
	} catch (Exception $e) {
		$outerTransaction->rollBack();
	}
	
	
	# where
    where('status=1')->  
    where('status=:status', [':status' => $status])->  
    where([  
        'status' => 10,  
        'type' => null,  
        'id' => [4, 8, 15],  
    ])->  
    -------  
    $userQuery = (new Query())->select('id')->from('user');  
    // ...WHERE `id` IN (SELECT `id` FROM `user`)  
    $query->...->where(['id' => $userQuery])->...  
    --------  
    ['and', 'id=1', 'id=2'] //id=1 AND id=2  
    ['and', 'type=1', ['or', 'id=1', 'id=2']] //type=1 AND (id=1 OR id=2)  
    ['between', 'id', 1, 10] //id BETWEEN 1 AND 10  
    ['not between', 'id', 1, 10] //not id BETWEEN 1 AND 10  
    ['in', 'id', [1, 2, 3]] //id IN (1, 2, 3)  
    ['not in', 'id', [1, 2, 3]] //not id IN (1, 2, 3)  
    ['like', 'name', 'tester'] //name LIKE '%tester%'  
    ['like', 'name', ['test', 'sample']] //name LIKE '%test%' AND name LIKE '%sample%'  
    ['not like', 'name', ['or', 'test', 'sample']] //not name LIKE '%test%' OR not name LIKE '%sample%'  
    ['exists','id', $userQuery] //EXISTS (sub-query) | not exists  
    ['>', 'age', 10] //age>10  
	
	�õ��ղ����id
	order_id = Yii::$app->db->getLastInsertID();
	
	$sql = 'SELECT * FROM  sales_flat_quote';
	Yii::$app->db->createCommand($sql,[])
					->queryAll();
		
		
	*/
	
}














