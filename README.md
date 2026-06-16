# Seeker - Robô de Candidaturas Automatizadas

O **Seeker** é um protótipo funcional de automação de processos (RPA) desenvolvido em Laravel. O objetivo do sistema é equilibrar o jogo no mercado de trabalho, permitindo que candidatos automatizem a busca e a triagem de vagas de emprego de forma autônoma, utilizando algoritmos baseados em cruzamento de dados (_skills matching_), sem depender exclusivamente dos critérios opacos dos softwares de RH tradicionais.

Este projeto foi desenvolvido estritamente como um Projeto Aplicado para fins acadêmicos. O objetivo principal é a validação prática do algoritmo de busca e a demonstração dos conceitos estudados ao longo do curso.

---

## Funcionalidades Atuais (Back-end Core)

- **Consumo de API Real:** Integração direta com a API internacional da Adzuna para captura dinâmica de vagas no Brasil.
- **Busca Dinâmica por Stack:** O robô adapta os parâmetros da requisição HTTP com base no array de habilidades cadastradas do candidato.
- **Algoritmo de Matching Profundo:** Varredura algorítmica rigorosa (título e descrição longa da vaga) para identificar palavras-chave e compatibilidade técnica, mitigando falsos positivos.
- **Decisão Autônoma:** Tomada de decisão lógica no back-end para aprovar a candidatura (`Candidatura Automatizada ✅`) ou descartar a oportunidade (`Ignorada ❌`).
- **Interface CLI (Terminal):** Exibição de relatórios detalhados estruturados em tabelas diretamente no console via Custom Artisan Command.
- **Fallback de Contingência:** Mecanismo de segurança que injeta dados locais simulados (mock) caso a API externa sofra instabilidade ou retorne vazia.

---

## Tecnologias Utilizadas

- **Framework PHP:** Laravel (v11.x)
- **Cliente HTTP:** Laravel Http Client (Guzzle integrado)
- **Interface de Linha de Comando:** Laravel Artisan Console
- **Ambiente de Desenvolvimento:** XAMPP / Servidor Local

---

### Pré-requisitos

- PHP >= 8.2 instalado
- Composer
- Chaves de desenvolvedor da API da Adzuna (obtidas gratuitamente em [developer.adzuna.com](https://developer.adzuna.com/))

### Instalação

Em breve, projeto ainda sendo desenvolvido.
