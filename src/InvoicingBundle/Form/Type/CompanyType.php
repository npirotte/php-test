<?php
namespace InvoicingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('email', 'email', array('attr' => array('data-autocomplete' => 'company/search')));
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'InvoicingBundle\Entity\Company'
    ));
  }

  public function getName()
  {
    return 'company';
  }
}
