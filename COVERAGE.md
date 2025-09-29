# 代码覆盖率配置

## 概述

本项目已配置代码覆盖率检查，要求最低覆盖率为80%。

## CI/CD 配置

### GitHub Actions

项目包含以下工作流：

1. **测试工作流** (`.github/workflows/test.yml`)
   - 在多个PHP版本和Laravel版本上运行测试
   - 包含覆盖率检查，要求最低80%覆盖率
   - 使用Xdebug生成覆盖率报告

2. **覆盖率工作流** (`.github/workflows/coverage.yml`)
   - 专门用于生成和上传覆盖率报告到Codecov
   - 生成Clover格式的覆盖率报告

## 本地开发

### 安装Xdebug

要在本地运行覆盖率测试，需要安装Xdebug：

```bash
# macOS (使用Homebrew)
brew install php@8.3-xdebug

# 或者使用PECL
pecl install xdebug
```

### 运行覆盖率测试

```bash
# 运行测试并检查覆盖率（要求最低80%）
composer test-coverage

# 生成HTML覆盖率报告
composer test-coverage-html

# 仅运行测试（不检查覆盖率）
composer test
```

## 配置文件

- `phpunit.xml` - PHPUnit配置，包含覆盖率设置
- `pest.php` - Pest配置文件
- `composer.json` - 包含测试脚本

## 覆盖率报告

- `coverage.xml` - Clover格式报告（用于CI）
- `coverage-html/` - HTML格式报告（用于本地查看）

这些文件已添加到`.gitignore`中，不会提交到版本控制。