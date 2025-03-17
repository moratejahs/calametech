import 'dart:convert';

import 'package:calamitech/constants/api_paths.dart';
import 'package:calamitech/features/sos_reports/models/sos_report.dart';
import 'package:http/http.dart' as http;

class SosReportsRepository {
  final http.Client httpClient;

  SosReportsRepository({
    required this.httpClient,
  });

  Future<List<SosReport>> getSosReports(String token) async {
    final response = await httpClient.get(
      Uri.parse("${ApiPaths.baseUrl}${ApiPaths.sos}"),
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $token',
      },
    );

    if (response.statusCode == 200) {
      final List sosReports = jsonDecode(response.body);

      return sosReports.map((sosReport) => SosReport.fromMap(sosReport)).toList();
    }

    throw Exception('Failed to fetch SOS reports');
  }

  Future<List<SosReport>> getSosFeaturedReports(String token) async {
    final response = await httpClient.get(
      Uri.parse("${ApiPaths.baseUrl}${ApiPaths.sosFeatured}"),
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $token',
      },
    );

    if (response.statusCode == 200) {
      final List sosReports = jsonDecode(response.body);

      return sosReports.map((sosReport) => SosReport.fromMap(sosReport)).toList();
    }

    throw Exception('Failed to fetch SOS reports');
  }

  Future<List<SosReport>> getSosRecoReports(String token) async {
    final response = await httpClient.get(
      Uri.parse("${ApiPaths.baseUrl}${ApiPaths.sosReco}"),
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $token',
      },
    );

    if (response.statusCode == 200) {
      final List sosReports = jsonDecode(response.body);

      return sosReports.map((sosReport) => SosReport.fromMap(sosReport)).toList();
    }

    throw Exception('Failed to fetch SOS reports');
  }
}
