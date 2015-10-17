<?php

namespace InvoicingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Invoice
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Invoice
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dueDate", type="date")
     */
    private $dueDate;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal")
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\User")
     * @ORM\JoinColumn(name="approvedBy_id", referencedColumnName="id")
     * @Assert\Null()
     */
    private $approvedBy = null;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="approvedOn", type="datetime", nullable=true)
     * @Assert\Null()
     */
    private $approvedOn = null;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="sendEmailOn", type="datetime", nullable=true)
     * @Assert\Null()
     */
    private $sendEmailOn = null;

    /* *** links *** */

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="emittedInvoices", cascade={"persist"})
     * @ORM\JoinColumn(name="seller_id", referencedColumnName="id")
     */
    private $seller;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="receivedInvoices", cascade={"persist"})
     * @ORM\JoinColumn(name="debtor_id", referencedColumnName="id")
     */
    private $debtor;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dueDate
     *
     * @param \DateTime $dueDate
     *
     * @return Invoice
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate
     *
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Invoice
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Invoice
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set seller
     *
     * @param \InvoicingBundle\Entity\Company $seller
     *
     * @return Invoice
     */
    public function setSeller(\InvoicingBundle\Entity\Company $seller = null)
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * Get seller
     *
     * @return \InvoicingBundle\Entity\Company
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * Set debtor
     *
     * @param \InvoicingBundle\Entity\Company $deptor
     *
     * @return Invoice
     */
    public function setDebtor(\InvoicingBundle\Entity\Company $debtor = null)
    {
        $this->debtor = $debtor;

        return $this;
    }

    /**
     * Get debtor
     *
     * @return \InvoicingBundle\Entity\Company
     */
    public function getDebtor()
    {
        return $this->debtor;
    }

    /**
     * Set approvedOn
     *
     * @param \DateTime $approvedOn
     *
     * @return Invoice
     */
    public function setApprovedOn($approvedOn)
    {
        $this->approvedOn = $approvedOn;

        return $this;
    }

    /**
     * Get approvedOn
     *
     * @return \DateTime
     */
    public function getApprovedOn()
    {
        return $this->approvedOn;
    }

    /**
     * Set approvedBy
     *
     * @param \AppBundle\Entity\User $approvedBy
     *
     * @return Invoice
     */
    public function setApprovedBy(\AppBundle\Entity\User $approvedBy = null)
    {
        $this->approvedBy = $approvedBy;

        return $this;
    }

    /**
     * Get approvedBy
     *
     * @return \InvoicingBundle\Entity\AppBundle:User
     */
    public function getApprovedBy()
    {
        return $this->approvedBy;
    }

    /**
     * Set sendEmailOn
     *
     * @param \DateTime $sendEmailOn
     *
     * @return Invoice
     */
    public function setSendEmailOn($sendEmailOn)
    {
        $this->sendEmailOn = $sendEmailOn;

        return $this;
    }

    /**
     * Get sendEmailOn
     *
     * @return \DateTime
     */
    public function getSendEmailOn()
    {
        return $this->sendEmailOn;
    }
}
