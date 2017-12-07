<?php
/**
 * IdpUser Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AuthShibbolethAppModel', 'AuthShibboleth.Model');

/**
 * IdpUser Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Model
 */
class IdpUser extends AuthShibbolethAppModel {

	///**
	// * @var string 表示順 新着順
	// */
	//	const DISPLAY_ORDER_NEW = 'new';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/ja/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		$this->validate = Hash::merge($this->validate, array(
			'user_id' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				),
			),
			// IdPによる個人識別番号
			'idp_userid' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				),
			),
			'is_shib_eptid' => array(
				'notBlank' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
					//'required' => true,
				),
			),
			'status' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * hasOne associations
 *
 * @var array
 */
	public $hasOne = array(
		'IdpUserProfile' => array(
			'className' => 'AuthShibboleth.IdpUserProfile',
			//'foreignKey' => false,
			//'conditions' => array(
			//	'IdpUser.id = IdpUserProfile.idp_user_id',
			//),
			//'fields' => '',
			//'order' => ''
		)
	);

	///**
	// * belongsTo associations
	// *
	// * @var array
	// */
	//	public $belongsTo = array(
	//		'Frame' => array(
	//			'className' => 'Frames.Frame',
	//			'foreignKey' => false,
	//			'conditions' => array(
	//				'Frame.key = VideoFrameSetting.frame_key',
	//			),
	//			'fields' => 'block_id',
	//			'order' => ''
	//		),
	//	);

	///**
	// * VideoFrameSettingデータ取得
	// *
	// * @param bool $created If True, the results of the Model::find() to create it if it was null
	// * @return array
	// */
	//	public function getVideoFrameSetting($created) {
	//		$conditions = array(
	//			'frame_key' => Current::read('Frame.key')
	//		);
	//
	//		$videoFrameSetting = $this->find('first', array(
	//			'recursive' => -1,
	//			'conditions' => $conditions,
	//		));
	//
	//		if ($created && ! $videoFrameSetting) {
	//			$videoFrameSetting = $this->create(array(
	//				'frame_key' => Current::read('Frame.key'),
	//			));
	//		}
	//
	//		return $videoFrameSetting;
	//	}

/**
 * 外部ID連携 データ保存
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveIdpUser($data) {
		//トランザクションBegin
		$this->begin();

		//バリデーション
		$this->set($data);
		if (! $this->validates()) {
			$this->rollback();
			return false;
		}

		try {
			// 保存
			if (! $idpUser = $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return $idpUser;
	}
}