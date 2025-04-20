class TipModel {
  final String content;
  final String type;
  final DateTime createdAt = DateTime.now();

  TipModel({
    required this.content,
    required this.type,
  });

  factory TipModel.fromJson(Map<String, dynamic> json) {
    return TipModel(
      content: json['content'] as String,
      type: json['type'] as String,
    );
  }
}