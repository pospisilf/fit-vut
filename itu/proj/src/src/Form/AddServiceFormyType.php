<?php
# Author: Filip Pospisil (xpospi0f)
namespace App\Form;

use App\Entity\ServiceOperation;
use App\Entity\ServiceRecord;
use App\Repository\ServiceOperationRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Flex\Unpack\Operation;

class AddServiceFormyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', BirthdayType::class, [
                'label'=> 'date',
                'widget' => 'single_text',
            ])
            ->add('mileage')
            ->add('operation', EntityType::class, [
                'class' => ServiceOperation::class,
                'query_builder' => function (ServiceOperationRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'group_by' => function($choice, $key, $value) {
                    if ($choice->getCategory()) {
                        return $choice->getCategory()->getName();
                    }
                },
                'choice_label' => 'name',
            ])
            ->add('note')
            ->add('price')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ServiceRecord::class,
        ]);
    }
}
