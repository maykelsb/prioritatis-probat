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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Doctrine\DBAL\Connection;

/**
 * Form to manage game data.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
class GameType extends AbstractType
{
    /**
     * @var Doctrine\DBAL\Connection
     */
    protected $db;


    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'Nome: '
        ])->add('description', TextareaType::class, [
            'label' => 'Descrição: '
        ])->add('designers', ChoiceType::class, [
            'label' => 'Designer: ',
            'choices' => $this->loadDesignerChoices(),
        ])->add('submit', SubmitType::class, [
            'attr' => ['class' => 'btn-primary']
        ]);
    }

    public function getName()
    {
        return self::class;
    }


    protected function loadDesignerChoices()
    {
        $sql = <<<DML
SELECT id,
       name
  FROM member
DML;
        $data = $this->db->fetchAll($sql);
        foreach (is_array($data)?$data:[] as $designer) {
            $choices[$designer['name']] = $designer['id'];
        }

        return $choices;
    }
}
