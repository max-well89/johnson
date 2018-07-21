<?php

/**
 * Пользователь, авторизующийся по конфигу
 */
class agConfigUser extends agAbstractUser {

	public function auth() {
		$users = sfYaml::load($this->context->getDir('config').'/users.yml');
		$currUser = $this->context->getController()->getAuthData();
		if (!isset($users[$currUser['user']])) {
			throw new agGlobalException('incorrect user', agAbstractApiController::AUTH_FAILED);
		}
		if ($users[$currUser['user']]['password'] != $currUser['password']) {
			throw new agGlobalException('incorrect password', agAbstractApiController::AUTH_FAILED);
		}
		
		$this->login = $currUser['user'];
		$this->role = $users[$currUser['user']]['role'];
		$this->attributes = $users[$currUser['user']]['attributes'];
	}
}
