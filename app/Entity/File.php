<?php

namespace Entity;

use DateTime;

class File
{
    protected int $id;
    protected string $name;
    protected string $path;
    protected DateTime $created;

	protected DateTime $updated;

	protected int|null $sender;

	protected int $userId;

	/**
	 * @param int $id 
	 * @return self
	 */
	public function setId(int $id): self {
		$this->id = $id;
		return $this;
	}
	
	/**
	 * @param string $name 
	 * @return self
	 */
	public function setName(string $name): self {
		$this->name = $name;
		return $this;
	}
	
	/**
	 * @param string $path 
	 * @return self
	 */
	public function setPath(string $path): self {
		$this->path = $path;
		return $this;
	}
	
	/**
	 * @param DateTime $created 
	 * @return self
	 */
	public function setCreated(DateTime $created): self {
		$this->created = $created;
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
	public function getName(): string {
		return $this->name;
	}
	
	/**
	 * @return string
	 */
	public function getPath(): string {
		return $this->path;
	}
	
	/**
	 * @return DateTime
	 */
	public function getCreated(): DateTime {
		return $this->created;
	}

	/**
	 * @return DateTime
	 */
	public function getUpdated(): DateTime {
		return $this->updated;
	}
	
	/**
	 * @return int|null
	 */
	public function getSender(): int|null {
		return $this->sender;
	}

	/**
	 * @param DateTime $updated 
	 * @return self
	 */
	public function setUpdated(DateTime $updated): self {
		$this->updated = $updated;
		return $this;
	}
	
	/**
	 * @param int|null $sender 
	 * @return self
	 */
	public function setSender(int|null $sender): self {
		$this->sender = $sender;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getUserId(): int {
		return $this->userId;
	}

	/**
	 * @param int $userId 
	 * @return self
	 */
	public function setUserId(int $userId): self {
		$this->userId = $userId;
		return $this;
	}
}