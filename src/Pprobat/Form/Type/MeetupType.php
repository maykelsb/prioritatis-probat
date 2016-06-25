<?php
/**
 * This file is part of Prioritatis Probat project.
 *
 * This is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3, or (at your option)
 * any later version.
 *
 * @link https://github.com/maykelsb/prioritatis-probat
 */
namespace Pprobat\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
//use Symfony\Component\Validator\Constraints as Assert;

/**
 * Form to manage meetup data.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
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
