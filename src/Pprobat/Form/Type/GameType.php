<?php
/**
 * Game creation form.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */

namespace Pprobat\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Doctrine\DBAL\Connection;


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
//            'multiple' => true
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
