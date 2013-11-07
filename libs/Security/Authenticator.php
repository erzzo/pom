<?php
namespace Security;
use Nette;
use Nette\Security\Identity;
use Nette\Object,
	Nette\Environment;
use Nette\Security\AuthenticationException;

class Authenticator extends Nette\Object implements Nette\Security\IAuthenticator {

	/** @var Nette\Database\Connection */
	private $database;
	private $inRole;

	/**
	 * @param Nette\Database\Connection
	 */
	public function __construct(Nette\Database\Connection $database)
	{
		$this->database = $database;
	}

	/**
	 * Performas an authentication
	 * @param array
	 * @return Nette\Security\Identity
	 */
	public function authenticate(array $credentials) {


		list($login, $password) = $credentials;
		$row = $this->database->table('user')->where('login', $login)->fetch();


		if (!$row) {
			throw new AuthenticationException('Zlý login.', self::IDENTITY_NOT_FOUND);
		}

		if ($row->password !== sha1($password)) {
				throw new AuthenticationException('Zlé heslo.', self::INVALID_CREDENTIAL);
		}

		$role = $this->database->table('role')->where('id', $row->role_id)->fetch();

		unset($row->password);
		return new Identity($row->id, $role->name, $row->toArray());
		//** Samotna authent. cez LDAP
		/*	$ldap_conn = ldap_connect('ldaps://ldap.fei.tuke.sk');
			if ($ldap_conn) {
				$ldapbind = @ldap_bind ($ldap_conn,'uid='.$login.',ou=People,dc=fei,dc=tuke,dc=sk', $password);
				ldap_unbind ($ldap_conn);
				if ($ldapbind) {
					//return new Identity($login, 'Lecturer', $ldapbind);

									 return new Identity($row->id, $inRole->role, $row->toArray(), $ldapbind);

				} else {
					throw new AuthenticationException("Zlé meno alebo heslo (LDAP).", self::INVALID_CREDENTIAL);
				}
			} else {
				throw new AuthenticationException("Overovací server nieje dostupný.", self::IDENTITY_NOT_FOUND);
			}*/
	}
}