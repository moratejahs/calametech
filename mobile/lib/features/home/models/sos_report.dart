import 'dart:convert';

class SosReport {
  final int id;
  final double lat;
  final double long;
  final String status;
  final String? type;
  final String? image;
  final String date;
  SosReport({
    required this.id,
    required this.lat,
    required this.long,
    required this.status,
    this.type,
    this.image,
    required this.date,
  });

  SosReport copyWith({
    int? id,
    double? lat,
    double? long,
    String? status,
    String? type,
    String? image,
    String? date,
  }) {
    return SosReport(
      id: id ?? this.id,
      lat: lat ?? this.lat,
      long: long ?? this.long,
      status: status ?? this.status,
      type: type ?? this.type,
      image: image ?? this.image,
      date: date ?? this.date,
    );
  }

  Map<String, dynamic> toMap() {
    return <String, dynamic>{
      'id': id,
      'lat': lat,
      'long': long,
      'status': status,
      'type': type,
      'image': image,
      'date': date,
    };
  }

  factory SosReport.fromMap(Map<String, dynamic> map) {
    return SosReport(
      id: map['id'] as int,
      lat: map['lat'] as double,
      long: map['long'] as double,
      status: map['status'] as String,
      type: map['type'] != null ? map['type'] as String : null,
      image: map['image'] != null ? map['image'] as String : null,
      date: map['date'] as String,
    );
  }

  String toJson() => json.encode(toMap());

  factory SosReport.fromJson(String source) => SosReport.fromMap(json.decode(source) as Map<String, dynamic>);

  @override
  String toString() {
    return 'SosReport(id: $id, lat: $lat, long: $long, status: $status, type: $type, image: $image, date: $date)';
  }

  @override
  bool operator ==(covariant SosReport other) {
    if (identical(this, other)) return true;

    return other.id == id &&
        other.lat == lat &&
        other.long == long &&
        other.status == status &&
        other.type == type &&
        other.image == image &&
        other.date == date;
  }

  @override
  int get hashCode {
    return id.hashCode ^ lat.hashCode ^ long.hashCode ^ status.hashCode ^ type.hashCode ^ image.hashCode ^ date.hashCode;
  }
}
