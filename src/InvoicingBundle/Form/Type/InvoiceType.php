<?php
namespace InvoicingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use InvoicingBundle\Form\Type\CompanyType;

class InvoiceType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('dueDate')
      ->add('amount')
      ->add('reference')
      ->add('seller', new CompanyType())
      ->add('debtor', new CompanyType())
      ->add('save', 'submit', array('label' => 'Record invoice'))
      ->getForm();
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'InvoicingBundle\Entity\Invoice'
    ));
  }

  public function getName()
  {
    return 'invoice';
  }
}
