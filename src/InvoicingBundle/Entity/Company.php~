<?php

namespace InvoicingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Company
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Company
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
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Email(
     *    message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="Invoice", mappedBy="seller")
     */
    protected $emittedInvoices;

    /**
     * @ORM\OneToMany(targetEntity="Invoice", mappedBy="deptor")
     */
    protected $receivedInvoices;

    /**
     * Populate emitted and received invoices
     */
     public function __construct()
     {
        $this->emittedInvoices = new ArrayCollection();
        $this->receivedInvoices = new ArrayCollection();
     }

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
     * Set email
     *
     * @param string $email
     *
     * @return Company
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Add emittedInvoice
     *
     * @param \InvoicingBundle\Entity\Invoice $emittedInvoice
     *
     * @return Company
     */
    public function addEmittedInvoice(\InvoicingBundle\Entity\Invoice $emittedInvoice)
    {
        $this->emittedInvoices[] = $emittedInvoice;

        return $this;
    }

    /**
     * Remove emittedInvoice
     *
     * @param \InvoicingBundle\Entity\Invoice $emittedInvoice
     */
    public function removeEmittedInvoice(\InvoicingBundle\Entity\Invoice $emittedInvoice)
    {
        $this->emittedInvoices->removeElement($emittedInvoice);
    }

    /**
     * Get emittedInvoices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmittedInvoices()
    {
        return $this->emittedInvoices;
    }

    /**
     * Add receivedInvoice
     *
     * @param \InvoicingBundle\Entity\Invoice $receivedInvoice
     *
     * @return Company
     */
    public function addReceivedInvoice(\InvoicingBundle\Entity\Invoice $receivedInvoice)
    {
        $this->receivedInvoices[] = $receivedInvoice;

        return $this;
    }

    /**
     * Remove receivedInvoice
     *
     * @param \InvoicingBundle\Entity\Invoice $receivedInvoice
     */
    public function removeReceivedInvoice(\InvoicingBundle\Entity\Invoice $receivedInvoice)
    {
        $this->receivedInvoices->removeElement($receivedInvoice);
    }

    /**
     * Get receivedInvoices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReceivedInvoices()
    {
        return $this->receivedInvoices;
    }
}
