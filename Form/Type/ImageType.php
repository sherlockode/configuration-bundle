<?php

namespace Sherlockode\ConfigurationBundle\Form\Type;

use Sherlockode\ConfigurationBundle\Manager\UploadManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    private UploadManagerInterface $uploadManager;

    public function __construct(UploadManagerInterface $uploadManager)
    {
        $this->uploadManager = $uploadManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                'label' => 'field_type.image.file',
                'required' => false,
                'translation_domain' => 'SherlockodeConfigurationBundle',
            ])
            ->add('delete', CheckboxType::class, [
                'required' => false,
                'label' => 'field_type.image.delete',
                'translation_domain' => 'SherlockodeConfigurationBundle',
            ])
        ;

        $filename = '';
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use (&$filename) {
                $data = $event->getData();
                if (isset($data['src'])) {
                    $filename = $data['src'];
                }
            }
        );

        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) use ($options, &$filename) {
                $data = $event->getData();
                if ($data['delete']) {
                    $data['src'] = '';
                    $this->uploadManager->remove($filename);
                }
                if ($data['file'] instanceof UploadedFile) {
                    if ($filename !== '') {
                        $this->uploadManager->remove($filename);
                    }
                    $data['src'] = $this->uploadManager->upload($data['file']);
                }
                unset($data['file']);
                unset($data['delete']);
                $event->setData($data);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('translation_domain', 'SherlockodeConfigurationBundle');
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $source = '';
        $data = $form->getData();
        if (!empty($data['src'])) {
            $source = $this->uploadManager->resolveUri($data['src']);
        }

        $view->vars['source'] = $source;

    }

    public function getBlockPrefix(): string
    {
        return 'config_image';
    }
}
