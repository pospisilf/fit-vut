<?php
# Author: Filip Pospisil (xpospi0f)
namespace App\Form;

use App\Entity\FuelRecord;
use App\Entity\GasStation;
use App\Repository\GasStationRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddRefuelingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount')
            ->add('price')
            ->add('mileage')
            ->add('date', BirthdayType::class, [
                'label'=> 'date',
                'widget' => 'single_text',
            ])
            ->add('gas_station',  EntityType::class, [
                'class' => GasStation::class,
                'query_builder' => function (GasStationRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FuelRecord::class,
        ]);
    }
}
