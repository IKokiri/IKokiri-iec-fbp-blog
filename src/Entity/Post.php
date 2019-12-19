<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
final class Post{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */

     private ?int $id = null;

     /**
      * @ORM\Column()
      */
      public string $title;

      /**
       * @ORM\Column()
       */
      public string $description;

      /**
       * @ORM\Column(type="datetime")
       */
      private \DateTime $createdAt;

      public function __construct(string $title, string $description)
      {
          $this->title = $title;
          $this->description = $description;
          $this->createdAt = new \DateTime();
      }

      public function getId(){
        return $this->id;
      }

      public function getCreatedAt(): \DateTime{
        return $this->createdAt;
      }
}