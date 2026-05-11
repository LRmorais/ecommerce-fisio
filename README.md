# Viva Leve — Child Theme

Child theme do WordPress para a **Viva Leve**, loja de produtos para fisioterapia, linfedema e lipedema. Baseado no tema pai **Storefront** com personalizações visuais e overrides de templates WooCommerce.

---

## Estrutura de Pastas

```
viva-leve-child/
├── style.css                        # Header do tema + variáveis CSS da marca
├── functions.php                    # Enqueue de assets + suporte ao tema
├── .gitignore                       # Arquivos ignorados pelo Git
│
├── assets/
│   ├── css/
│   │   └── custom.css               # Estilos personalizados por seção
│   ├── js/
│   │   └── custom.js                # Scripts personalizados por seção
│   └── img/                         # Imagens do tema (logo, ícones etc.)
│
└── woocommerce/                     # Overrides de templates WooCommerce
    ├── archive-product.php          # Vitrine / listagem de produtos
    ├── single-product.php           # Página individual do produto
    ├── cart/
    │   └── cart.php                 # Página do carrinho
    └── checkout/
        └── form-checkout.php        # Formulário de checkout
```

---

## Como Ativar o Child Theme no Painel WP

1. Acesse **Painel WordPress → Aparência → Temas**
2. Localize o tema **Viva Leve Child**
3. Clique em **Ativar**
4. Confirme que o tema pai **Storefront** está instalado

> O tema pai (Storefront) não precisa estar ativo — apenas instalado.

---

## Como Fazer Override de Templates WooCommerce

O WooCommerce busca templates na seguinte ordem de prioridade:

1. `wp-content/themes/viva-leve-child/woocommerce/`
2. `wp-content/themes/storefront/woocommerce/`
3. `wp-content/plugins/woocommerce/templates/`

**Para sobrescrever um template:**

1. Localize o arquivo original em:
   ```
   ~/Local Sites/viva-leve/app/public/wp-content/plugins/woocommerce/templates/
   ```

2. Copie-o mantendo a mesma estrutura de subpastas para:
   ```
   ~/Local Sites/viva-leve/app/public/wp-content/themes/viva-leve-child/woocommerce/
   ```

3. Edite o arquivo copiado — nunca edite o original do plugin.

**Exemplos de templates comuns:**

| Template original (em `woocommerce/templates/`) | Destino no child theme |
|---|---|
| `archive-product.php` | `woocommerce/archive-product.php` |
| `single-product.php` | `woocommerce/single-product.php` |
| `cart/cart.php` | `woocommerce/cart/cart.php` |
| `checkout/form-checkout.php` | `woocommerce/checkout/form-checkout.php` |
| `myaccount/my-account.php` | `woocommerce/myaccount/my-account.php` |

---

## Como Usar as Variáveis CSS da Marca

As variáveis estão definidas em `style.css` e disponíveis em qualquer seletor CSS:

```css
/* Exemplo de uso em custom.css */
.meu-componente {
    background-color: var(--cor-primaria);
    color: var(--cor-fundo);
    border-radius: var(--raio-borda);
    padding: var(--espacamento-base);
    font-family: var(--fonte-principal);
}
```

| Variável | Valor padrão | Uso |
|---|---|---|
| `--cor-primaria` | `#5ba4a0` | Botões, links, destaques |
| `--cor-secundaria` | `#a8d5d1` | Hover, badges, apoio |
| `--cor-texto` | `#333333` | Texto corrido e títulos |
| `--cor-fundo` | `#ffffff` | Fundo de página e cards |
| `--fonte-principal` | `'Segoe UI', sans-serif` | Tipografia geral |
| `--raio-borda` | `8px` | Botões, cards, inputs |
| `--espacamento-base` | `1rem` | Margens e paddings |

Para alterar a identidade visual, edite apenas o `style.css` — as mudanças se propagam automaticamente.

---

## Comandos Git Iniciais

```bash
# Entrar na pasta do child theme
cd ~/Local\ Sites/viva-leve/app/public/wp-content/themes/viva-leve-child

# Inicializar repositório Git
git init

# Adicionar todos os arquivos
git add .

# Criar o primeiro commit
git commit -m "init: estrutura base do child theme Viva Leve"

# (Opcional) Conectar ao repositório remoto
git remote add origin https://github.com/seu-usuario/viva-leve-child.git
git branch -M main
git push -u origin main
```
