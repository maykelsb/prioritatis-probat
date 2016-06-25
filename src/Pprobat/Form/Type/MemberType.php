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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
//use Symfony\Component\Validator\Constraints as Assert;

/**
 * Form to manage member data.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
                'label' => 'Nome: '
            ])
            ->add('affiliation', DateType::class, [
                'label' => 'Data de afiliação: '
            ])
            ->add('about', TextareaType::class, [
                'label' => 'Sobre você: '
            ])
            ->add('username', TextType::class, [
                'label' => 'Nome de usuário: '
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Senha: '
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn-primary']
            ]);
    }

    public function getName()
    {
        return self::class;
    }
}