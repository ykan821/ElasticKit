# ElasticKit - Elasticsearch DSL Query Builder

PHP Elasticsearch DSL 查询构建库。

> 本文件提交到仓库。本地环境变量放在 `CLAUDE.local.md`（已 gitignore），Claude Code 自动加载两者。

**版本管理：** a.b.c，a 对齐 ES 主版本，`^8` 即可。

> master 对应 v8.x（ES 8.x，PHP 8.1+），7.x 分支独立维护（ES 7.x，PHP 7.2+）。两条线不互合并，CLAUDE.md 各分支独立维护。

### 提交信息规范

- **参数名锁定**：公开方法参数名是 API 的一部分（支持命名参数），minor 版本禁止重命名

[Conventional Commits](https://www.conventionalcommits.org/)，中文描述：`feat(query): 新增 knn 向量搜索`

Scope 可选：dsl / index / agg / query / docs。Breaking change 加 `!` 后缀。

### Changelog 规范

[Keep a Changelog](https://keepachangelog.com)，中文分类：

- **新增** / **变更** / **弃用** / **移除** / **修复** / **安全**
- 只记录对用户有影响的变更
- 相关改动合并为一条
- Breaking change 以 `**BC:**` 前缀标记

### 发版流程

1. 跑全部测试
2. 更新 CHANGELOG.md
3. 提交并推送
4. 确认版本号后打 tag 并推送

### PHPDoc 规范

PSR-5 规范。

## 测试

测试在 Docker 容器中运行，需要设置以下环境变量：

| 变量 | 用途 |
|---|---|
| `PHP_CONTAINER` | Docker 容器名 |
| `PROJECT_PATH` | 项目在容器内的路径 |
| `PROXY_PORT` | HTTP 代理端口（推送用） |

```bash
docker exec $PHP_CONTAINER sh -c "cd $PROJECT_PATH && vendor/bin/phpunit --testsuite unit"
docker exec $PHP_CONTAINER sh -c "cd $PROJECT_PATH && vendor/bin/phpunit --testsuite index"
docker exec $PHP_CONTAINER sh -c "cd $PROJECT_PATH && vendor/bin/phpunit"
docker exec $PHP_CONTAINER sh -c "cd $PROJECT_PATH && vendor/bin/phpstan analyse"
docker exec $PHP_CONTAINER sh -c "cd $PROJECT_PATH && vendor/bin/phpmd src text phpmd.xml"
docker exec $PHP_CONTAINER sh -c "cd $PROJECT_PATH && PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix --diff"

# GitHub 不可达时走代理
https_proxy=http://127.0.0.1:$PROXY_PORT http_proxy=http://127.0.0.1:$PROXY_PORT git push
```
