# Changelog

## [8.0.0-beta.4] - 2026-06-07

### 新增

- DSL 查询构建器，支持多态参数（字符串/数组/闭包/对象）
- 全量查询类型覆盖：TermLevel、FullText、Compound、Geo、Joining、Span、Shape、Specialized
- 聚合支持：Bucket、Metric、Pipeline 三大类
- 搜索参数：sort、highlight、rescore、collapse、suggest、post_filter、knn 等
- Index 层：CRUD、分页、游标遍历、批量写入（Bulk）、零停机重建（Rebuild）
- 事件系统：搜索、批量操作、重建各阶段的事件监听
- OOP 风格：每个查询/聚合类型独立 Node 类，支持链式调用和增量构建
- 原生 DSL 透传：未覆盖的 ES 特性直接传数组
