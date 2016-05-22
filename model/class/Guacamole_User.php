<?php

/**
 * Description of Guacamole_User
 *
 * @author Alexis
 * @author Aurelie
 */

class Guacamole_User 
{
    /*
      ==============================
      ========= ATTRIBUTS ==========
      ==============================
     */
    
    /*
     * Id de l'utilisateur
     * @var int 
     */
    
    private $userId;
    
    /*
     * Nom de l'utilisateur
     * @var string
     */
    private $username;
    
    /*
     * password_hash de l'utilisateur
     * @var string
     */
    private $passwordHash;
    
    /*
     * password_salt de l'utilisateur
     * @var string
     */
    private $passwordSalt;
    
    /*
     * disabled de l'utilisateur
     * @var string
     */
    private $disabled;
    
    /*
     * expired de l'utilisateur
     * @var string
     */
    private $expired;
    
    /*
     * accessWindowStart de l'utilisateur
     * @var string
     */
    private $accessWindowStart;
    
    /*
     * accessWindowEnd de l'utilisateur
     * @var string
     */
    private $accessWindowEnd;
    
    /*
     * validFrom; de l'utilisateur
     * @var string
     */
    private $validFrom;
    
    /*
     * validUntil de l'utilisateur
     * @var string
     */
    private $validUntil;
    
    /*
     * timezone de l'utilisateur
     * @var string
     */
    private $timezone;
    
    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     */
    
    public function Guacamole_Connection_Parameter(
    $userId = -1, $username ="Il n'y a pas de nom d'utilisateur", $passwordHash="Il n'y a pas de passwordHash", 
            $passwordSalt="Il n'y a pas de passwordSalt", $disabled="Rien", $expired="Rien",
            $accessWindowStart="00:00:00", $accessWindowEnd="00:00:00",
            $validFrom="0000-00-00", $validUntil="0000-00-00",
            $timezone="Il n'y a pas de timezone"
    )
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->passwordHash = $passwordHash;
        $this->passwordSalt = $passwordSalt;
        $this->disabled = $disabled;
        $this->expired = $expired;
        $this->accessWindowStart = $accessWindowStart;
        $this->accessWindowEnd = $accessWindowEnd;
        $this->validFrom = $validFrom;
        $this->validUntil = $validUntil;
        $this->timezone = $timezone;
    }
    
    /*
      ==============================
      ========== METHODES ==========
      ==============================
     */

    public function hydrate($dataSet)
    {
        $this->userId = $dataSet['user_id'];
        $this->username = $dataSet['username'];
        $this->passwordHash = $dataSet['password_hash'];
        $this->passwordSalt = $dataSet['password_salt'];
        $this->disabled = $dataSet['disabled'];
        $this->expired = $dataSet['expired'];
        $this->accessWindowStart = $dataSet['access_window_start'];
        $this->accessWindowEnd = $dataSet['access_window_end'];
        $this->validFrom = $dataSet['valid_from'];
        $this->validUntil = $dataSet['valid_until'];
        $this->timezone = $dataSet['timezone'];
    }
    
    /*
      ==============================
      ======= GETTER/SETTER ========
      ==============================
     */

    //userId
    public function setUserId($userId)
    {
        if (is_int($userId))
        {
            $this->userId = $userId;
        }
    }

    public function getUserId()
    {
        return $this->userId;
    }
    
    //username
    public function setUsername($username)
    {
        if (is_string($username))
        {
            $this->username = $username;
        }
    }

    public function getUsername()
    {
        return $this->username;
    }
    
    //passwordHash
    public function setPasswordHash($passwordHash)
    {
        if (is_string($passwordHash))
        {
            $this->passwordHash = $passwordHash;
        }
    }

    public function getPasswordHash()
    {
        return $this->passwordHash;
    }
    
    //passwordSalt
    public function setPasswordSalt($passwordSalt)
    {
        if (is_string($passwordSalt))
        {
            $this->passwordSalt = $passwordSalt;
        }
    }

    public function getPasswordSalt()
    {
        return $this->passwordSalt;
    }
    
    //disabled
    public function setDisabled($disabled)
    {
        if (is_string($disabled))
        {
            $this->disabled = $disabled;
        }
    }

    public function getDisabled()
    {
        return $this->disabled;
    }
    
    //expired
    public function setExpired($expired)
    {
        if (is_string($expired))
        {
            $this->expired = $expired;
        }
    }

    public function getExpired()
    {
        return $this->expired;
    }
    
    //accessWindowStart
    public function setAccessWindowStart($accessWindowStart)
    {
        if (is_string($accessWindowStart))
        {
            $this->accessWindowStart = $accessWindowStart;
        }
    }

    public function getAccessWindowStart()
    {
        return $this->accessWindowStart;
    }
    
    //accessWindowEnd
    public function setAccessWindowEnd($accessWindowEnd)
    {
        if (is_string($accessWindowEnd))
        {
            $this->accessWindowEnd = $accessWindowEnd;
        }
    }

    public function getAccessWindowEnd()
    {
        return $this->accessWindowEnd;
    }
    
    //validFrom
    public function setValidFrom($validFrom)
    {
        if (is_string($validFrom))
        {
            $this->validFrom = $validFrom;
        }
    }

    public function getValidFrom()
    {
        return $this->validFrom;
    }
    
    //validUntil
    public function setValidUntil($validUntil)
    {
        if (is_string($validUntil))
        {
            $this->validUntil = $validUntil;
        }
    }

    public function getValidUntil()
    {
        return $this->validUntil;
    }
    
    //timezone
    public function setTimezone($timezone)
    {
        if (is_string($timezone))
        {
            $this->timezone = $timezone;
        }
    }

    public function getTimezone()
    {
        return $this->timezone;
    }
}
