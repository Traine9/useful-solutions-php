<?php

namespace AppBundle\Entity\Erp;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Erp\Repository\PointPeriodRepository")
 * @ORM\Table(name="erp_point_period",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="index_client_id_point_id_date_time_is_dst_second_hour",
 *             columns={"client_id", "point_id", "date", "time", "is_dst_second_hour"}
 *         )
 *     }
 * )
 */
class PointPeriod
{
    /**
     * @var int|null
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="client_id", type="integer", nullable=false)
     */
    protected $clientId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="point_id", type="string", nullable=false, length=63)
     */
    protected $pointId;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    protected $date;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(name="time", type="time", nullable=false)
     */
    protected $time;

    /**
     * @var float|null
     *
     * @ORM\Column(name="value", type="float", nullable=false)
     */
    protected $value;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_dst_second_hour", type="boolean", nullable=false, options={"default": 0})
     */
    protected $isDstSecondHour = false;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(name="time_create", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="create")
     */
    protected $timeCreate;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     *
     * @return PointPeriod
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    /**
     * @param int|null $clientId
     *
     * @return PointPeriod
     */
    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param \DateTimeInterface|null $date
     *
     * @return PointPeriod
     */
    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    /**
     * @param \DateTimeInterface|null $time
     *
     * @return PointPeriod
     */
    public function setTime(?\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getValue(): ?float
    {
        return $this->value;
    }

    /**
     * @param float|null $value
     *
     * @return PointPeriod
     */
    public function setValue(?float $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPointId(): ?string
    {
        return $this->pointId;
    }

    /**
     * @param string|null $pointId
     *
     * @return PointPeriod
     */
    public function setPointId(?string $pointId): self
    {
        $this->pointId = $pointId;

        return $this;
    }

    public function getDateTime()
    {
        return Carbon::instance($this->date)->setTimeFrom($this->time);
    }

    /**
     * @return bool|null
     */
    public function getIsDstSecondHour(): ?bool
    {
        return $this->isDstSecondHour;
    }

    /**
     * @param bool|null $isDstSecondHour
     *
     * @return self
     */
    public function setIsDstSecondHour(?bool $isDstSecondHour): self
    {
        $this->isDstSecondHour = $isDstSecondHour;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getTimeCreate(): ?\DateTimeInterface
    {
        return $this->timeCreate;
    }

    /**
     * @param \DateTimeInterface|null $timeCreate
     *
     * @return self
     */
    public function setTimeCreate(?\DateTimeInterface $timeCreate): self
    {
        $this->timeCreate = $timeCreate;

        return $this;
    }

    public function __toString()
    {
        return $this->date->format('Y-m-d H:i:s') . $this->isDstSecondHour;
    }
}
