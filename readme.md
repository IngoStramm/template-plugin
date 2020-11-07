# Template Plugin

Modelo de desenvolvimento de plugin para o WordPress.

### Description

Este é um modelo para desenvolvimento de plugin para o WordPress, com atualização automática integrada com o WordPress. Ele utiliza a biblioteca [Plugin Update Checker
](https://github.com/YahnisElsts/plugin-update-checker) para fazer a integração do Github com o WordPress.

### Pacotes

Navegue até o diretório `/src`, instale o gerenciador de pacotes do Node.js e os seguintes pacotes:

- npm `npm install`
- grunt `npm install grunt`
- node-sass `npm install node-sass grunt-sass`

### Nomes

Os seguintes prefixos foram usados e podem ser subsituídos através de um **search and replace**:

- Template Plugin (Nome do plugin)
- template-plugin (nome dos arquivos e usado na url do repositório)
- text-domain (text domain)
- PREFIX_ (prefixo usado nas constantes de Url e diretório do plugin, além das classes)
- prefix_ (prefixo usado nas funções)

##### Obs: também é necessário renomear os arquivos .js e .css

### Grunt

Estas são as tasks utilizadas:

- `grunt`: task default - gera a versão minificada dos arquivos **.css** e **.js**
- `grunt o`: otimiza as imagens
- `grunt c`: executa as task anteriores e gera um arquivo **.zip** no diretório `/dist` (este será o arquivo usado para atualizar o plugin no Wordpress)
- `grunt w`: executa o `livereload.js` - já vem com um array de IPs para funcionar apenas em ambiente de desenvolviemnto (adicione o seu IP no array, caso necessário)

### Includes

Neste modelo de plugin, já vem incluso as seguintes bibliotecas/módulos

- [TGM Plugin Activation](http://tgmpluginactivation.com/): biblioteca para dependências de outros plugins do Wordpress - por padrão vem com o [CMB2](https://wordpress.org/plugins/cmb2/)
- `class-post-type.php`: classe para a criação de **Custom Post Type**
- `class-taxonomy.php`: classe para a criação de **Custom Taxonomy**

### Referências

Parte do código usado neste plugin, foi retirado do [Odin Framework](https://github.com/wpbrasil/odin), o melhor tema-base de desenvolvimento para WordPress, desenvolvido e mantido pelo [WordPress Brasil](https://www.facebook.com/groups/wordpress.brasil).