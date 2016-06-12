<?php
/**
 * Meetup creation form.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */

namespace Pprobat\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
//use Symfony\Component\Validator\Constraints as Assert;

class MeetupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'Título: '
        ])
        ->add('local', TextType::class, [
            'label' => 'Local: '
        ])
        ->add('notes', TextareaType::class, [
            'label' => 'Observações e instruções: ',
            'required' => false
        ])
        ->add('happening', DateTimeType::class, [
            'label' => 'Quando: '
        ])
        ->add('report', TextareaType::class, [
            'label' => 'Session report: ',
            'required' => false
        ])
        ->add('meetuptype', ChoiceType::class, [
           'label' => 'Tipo de encontro: ',
            'choices' => [
                'Principal' => 'P',
                'Especial' => 'S',
                'Outro' => 'O'
            ]
        ])->add('submit', SubmitType::class, [
            'attr' => ['class' => 'btn-primary']
        ]);
    }

    public function getName()
    {
        return self::class;
    }
}
