// ignore_for_file: public_member_api_docs, sort_constructors_first
import 'dart:convert';

class SosReport {
  final int id;
  final String lat;
  final String long;
  final String status;
  final String? address;
  final String? type;
  final String? image;
  final String date;
  SosReport({
    required this.id,
    required this.lat,
    required this.long,
    required this.status,
    this.address,
    this.type,
    this.image,
    required this.date,
  });

  SosReport copyWith({
    int? id,
    String? lat,
    String? long,
    String? status,
    String? address,
    String? type,
    String? image,
    String? date,
  }) {
    return SosReport(
      id: id ?? this.id,
      lat: lat ?? this.lat,
      long: long ?? this.long,
      status: status ?? this.status,
      address: address ?? this.address,
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
      'address': address,
      'type': type,
      'image': image,
      'date': date,
    };
  }

  factory SosReport.fromMap(Map<String, dynamic> map) {
    return SosReport(
      id: map['id'] as int,
      lat: map['lat'] as String,
      long: map['long'] as String,
      status: map['status'] as String,
      address: map['address'] != null ? map['address'] as String : null,
      type: map['type'] != null ? map['type'] as String : null,
      image: map['image'] != null ? map['image'] as String : null,
      date: map['date'] as String,
    );
  }

  String toJson() => json.encode(toMap());

  factory SosReport.fromJson(String source) => SosReport.fromMap(json.decode(source) as Map<String, dynamic>);

  @override
  String toString() {
    return 'SosReport(id: $id, lat: $lat, long: $long, status: $status, address: $address, type: $type, image: $image, date: $date)';
  }

  @override
  bool operator ==(covariant SosReport other) {
    if (identical(this, other)) return true;

    return other.id == id &&
        other.lat == lat &&
        other.long == long &&
        other.status == status &&
        other.address == address &&
        other.type == type &&
        other.image == image &&
        other.date == date;
  }

  @override
  int get hashCode {
    return id.hashCode ^ lat.hashCode ^ long.hashCode ^ status.hashCode ^ address.hashCode ^ type.hashCode ^ image.hashCode ^ date.hashCode;
  }
}
