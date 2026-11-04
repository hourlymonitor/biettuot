# BiếtTuốt

BiếtTuốt là nền tảng AI giúp người dùng:
- hỏi đáp bằng tiếng Việt
- tạo nội dung theo nhiều mẫu
- khám phá kiến thức và quiz
- đóng góp nội dung cộng đồng
- tích điểm và đổi quà

## Mục tiêu

Xây dựng nền tảng AI phong phú, dễ mở rộng theo mô-đun, phù hợp cho việc triển khai trên GitHub và Hostinger, có thể phát triển từ MVP sang sản phẩm có cộng đồng và tính năng nâng cao.

## Tính năng dự kiến

### 1. Người dùng
- Chatbot hỏi đáp AI
- AI tạo bài, caption, email, kịch bản video
- Tóm tắt nội dung và tài liệu
- Gia sư AI / quiz / flashcard
- Trợ lý đời sống và công việc
- Tạo nội dung đa định dạng
- Chia sẻ và báo lỗi

### 2. Cộng tác viên
- Đóng góp nội dung, quiz, FAQ
- Duyệt nội dung bởi biên tập viên
- Tích điểm cho các đóng góp được duyệt
- Xếp hạng và huy hiệu

### 3. Quản trị
- Dashboard tổng quan
- Quản lý người dùng, vai trò và quyền
- Quản lý mô-đun và cấu hình AI
- Quản lý nội dung và kiểm duyệt
- Quản lý điểm, reward và đổi quà
- Nhật ký hoạt động hệ thống

## Kiến trúc đề xuất

### Stack gợi ý
- Backend: Laravel (PHP)
- Database: MySQL
- Frontend: Blade + Livewire hoặc React/Vue nếu cần UI mạnh hơn
- AI: Gateway layer gọi OpenAI / Anthropic / Gemini
- Deploy: GitHub + Hostinger

### Nguyên tắc xây dựng
- Modular monolith: mỗi tính năng là một module rõ ràng
- Dễ thêm module mới mà không phá hệ thống
- AI Gateway làm lớp trung gian giữa app và nhà cung cấp AI
- Quản trị viên có thể bật/tắt module, cấu hình mô hình và giới hạn sử dụng

## Cấu trúc thư mục gợi ý

```text
biettuot/
├── app/
│   ├── Console/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   ├── Models/
│   ├── Services/
│   │   ├── Ai/
│   │   ├── Chat/
│   │   ├── Content/
│   │   ├── Contributors/
│   │   ├── Points/
│   │   ├── Rewards/
│   │   └── Admin/
│   ├── Repositories/
│   ├── Policies/
│   ├── Notifications/
│   └── Jobs/
├── routes/
│   ├── api.php
│   ├── web.php
│   └── admin.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── js/
│   ├── css/
│   └── views/
├── tests/
├── .github/
│   └── workflows/
├── .env.example
├── README.md
└── composer.json
```

## Module đề xuất

- auth
- ai-gateway
- chatbot
- content
- moderation
- contributors
- points
- rewards
- admin
- notifications
- analytics

## Module tri thức / AI mở rộng

- gia sư AI
- tóm tắt tài liệu
- quiz
- luyện ngoại ngữ
- trợ lý du lịch
- trợ lý nấu ăn
- trợ lý việc làm/CV
- tạo nội dung mạng xã hội
- kịch bản video/podcast
- AI tạo hình ảnh

## Quy trình triển khai gợi ý

### Giai đoạn 1: Nền tảng
- Auth + role + permission
- AI gateway
- Chatbot cơ bản
- Dashboard admin
- Lưu lịch sử hội thoại

### Giai đoạn 2: Nội dung
- AI viết bài
- Draft + preview
- Duyệt nội dung
- Publish nội dung
- Chia sẻ link / ảnh

### Giai đoạn 3: Cộng đồng
- Cộng tác viên
- Đóng góp nội dung
- Tích điểm
- Đổi quà
- Leaderboard

### Giai đoạn 4: Mở rộng
- Trợ lý chuyên việc
- Quiz / thử thách
- Analytics
- Việc làm, du lịch, học tập, ngoại ngữ

## Lộ trình khuyến nghị

### Sprint 1
- setup repo GitHub
- Laravel setup
- auth + roles
- AI gateway
- dashboard admin cơ bản

### Sprint 2
- chatbot cơ bản
- lưu lịch sử
- rate câu trả lời
- gợi ý hỏi tiếp

### Sprint 3
- content draft
- review workflow
- publish content
- share link

### Sprint 4
- contributors
- points
- rewards
- leaderboard

### Sprint 5
- UI hoàn thiện
- analytics
- deployment Hostinger
- backup, security

## Bảo mật và vận hành

- Không commit API key, token hoặc secret vào GitHub
- Khai báo biến môi trường bằng `.env`
- dùng GitHub Actions cho CI/CD nếu cần
- backup database và files định kỳ
- kiểm soát quyền admin và moderator
- log mọi thay đổi quan trọng
- hạn chế quyền AI provider trong môi trường production

## Dữ liệu chính cần có

- users
- roles
- permissions
- chat_sessions
- chat_messages
- content_posts
- content_reviews
- contributor_profiles
- point_ledgers
- rewards
- reward_claims
- ai_provider_configs
- ai_model_configs
- notifications
- system_logs

## Mục tiêu MVP

MVP nên tập trung vào 4 phần cốt lõi:
1. tài khoản và quyền
2. AI gateway
3. chatbot hỏi đáp
4. content draft + review + publish

Đây là nền tảng đủ mạnh để thêm cộng tác viên, điểm và quà mà không phải phá cấu trúc.

## Địa điểm triển khai

- GitHub: lưu mã nguồn, quản lý issue và release
- Hostinger: chạy website + backend + database

## Kết luận

BiếtTuốt có thể phát triển thành nền tảng AI đa mô-đun, dễ mở rộng theo nhu cầu. Cốt lõi nên là:
- hỏi đáp
- tạo nội dung
- kiểm duyệt và cộng đồng
- điểm thưởng và đổi quà

Mọi module mới nên được thêm theo nguyên tắc rõ ràng: module, config, permission, UI, data, test.

---

Tài liệu này là bản khởi đầu để bạn triển khai trên GitHub và bắt đầu code.
