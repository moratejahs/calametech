class NewsModel {
  final String imagePath;
  final String title;
  final String description;
  final String? url;

  NewsModel({
    required this.imagePath,
    required this.title,
    required this.description,
    this.url,
  });

  factory NewsModel.fromJson(Map<String, dynamic> json) {
    return NewsModel(
      imagePath: json['image_path'] as String,
      title: json['title'] as String,
      description: json['description'] as String,
      url: json['url'] as String?,
    );
  }
}
