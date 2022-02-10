<?php
# Author: Filip Pospisil (xpospi0f)
namespace App\Form;

use App\Entity\Brand;
use App\Entity\Color;
use App\Entity\Engine;
use App\Entity\Model;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Repository\BrandRepository;
use App\Repository\ModelRepository;
use Doctrine\DBAL\Types\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddVehicleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('SPZ', TextType::class, [
                'label' => 'SPZ',
            ])
            ->add('VIN', TextType::class, [
                'label' => 'VIN',
            ])
            ->add('nickname', TextType::class, [
                'label' => 'Nickname',
            ])
            ->add('year', NumberType::class, [
                'label' => 'Year',
            ])
            ->add('fuel', ChoiceType::class, [
                'choices'  => [
                    'petrol' => 'petrol',
                    'diesel' => 'diesel',
                    'hybrid' => 'hybrid',
                    'electro' => 'electro',
                ],
            ])
            ->add('transmition', ChoiceType::class, [
                'choices'  => [
                    'manual' => 'manual',
                    'automatic' => 'automatic',
                    'semi-automatic' => 'semi-automatic',
                ],
            ])
            ->add('wheeldrive', ChoiceType::class, [
                'choices'  => [
                    'RWD' => 'RWD',
                    'FWD' => 'FWD',
                    'AWD' => 'AWD',
                ],
            ])
            ->add('odometer')
            ->add('brand', EntityType::class, [
                'class' => Brand::class,
                //'choices' => 
                'query_builder' => function (BrandRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add('color', EntityType::class, [
                'class' => Color::class,
                'choice_label' => 'name',
            ])
            ->add('model', EntityType::class, [
                'class' => Model::class,
                'query_builder' => function (ModelRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.brand', 'ASC');
                },
                'group_by' => function($choice, $key, $value) {
                    if ($choice->getBrand()) {
                        return $choice->getBrand()->getName();
                    }
                },
                'choice_label' => 'name',
            ])
            ->add('engine', EntityType::class, [
                'class' => Engine::class,
                'choice_label' => 'code',
            ])
            ->add ('stk', BirthdayType::class,[
                'label'=> 'date',
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
