<?php

namespace Entity;

class User{
    protected int $id;
    protected string $userName;
    protected string $email;
    protected string $password;



	/**
	 * @param int $id 
	 * @return self
	 */
	public function setId(int $id): self {
		$this->id = $id;
		return $this;
	}
	
	/**
	 * @param string $userName 
	 * @return self
	 */
	public function setUserName(string $userName): self {
		$this->userName = $userName;
		return $this;
	}
	
	/**
	 * @param string $email 
	 * @return self
	 */
	public function setEmail(string $email): self {
		$this->email = $email;
		return $this;
	}
	
	/**
	 * @param string $password 
	 * @return self
	 */
	public function setPassword(string $password): self {
		$this->password = $password;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	/**
	 * @return string
	 */
	public function getUserName(): string {
		return $this->userName;
	}
	
	/**
	 * @return string
	 */
	public function getEmail(): string {
		return $this->email;
	}
	
	/**
	 * @return string
	 */
	public function getPassword(): string {
		return $this->password;
	}
}