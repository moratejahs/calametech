import 'package:calamitech/constants/asset_paths.dart';
import 'package:flutter/material.dart';
import 'package:calamitech/constants/api_paths.dart';
import 'package:calamitech/features/sos_reports/models/sos_report.dart';

class SosCard extends StatelessWidget {
  final SosReport sosReport;
  final bool fullWidth;

  const SosCard({super.key, required this.sosReport, this.fullWidth = false});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: fullWidth ? MediaQuery.of(context).size.width - 32 : 200,
      height: 150, // Add fixed height to prevent unbounded height error
      margin: const EdgeInsets.only(right: 12),
      child: Card(
        elevation: 3,
        clipBehavior: Clip.antiAlias,
        child: Stack(
          fit: StackFit.expand, // Ensure stack fills parent
          children: [
            // Image background
            sosReport.image != null
                ? Image.network(
                    '${ApiPaths.storage}${sosReport.image}',
                    fit: BoxFit.cover,
                    errorBuilder: (context, error, stackTrace) => Container(
                      color: Colors.grey[300],
                      child: const Icon(Icons.broken_image),
                    ),
                  )
                : Container(
                    color: Colors.blueGrey[300],
                    child: const Icon(Icons.broken_image),
                  ),

            // Semi-transparent overlay for text readability
            Positioned.fill(
              child: Container(
                decoration: const BoxDecoration(
                  gradient: LinearGradient(
                    begin: Alignment.topCenter,
                    end: Alignment.bottomCenter,
                    colors: [
                      Colors.transparent,
                      Colors.black54, // Darker gradient for better readability
                    ],
                  ),
                ),
              ),
            ),

            // Text content
            Positioned(
              bottom: 0,
              left: 0,
              right: 0,
              child: Padding(
                padding: const EdgeInsets.all(12.0),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      mainAxisSize: MainAxisSize.min, // Use minimum space needed
                      children: [
                        Row(
                          children: [
                            const Icon(
                              Icons.location_on,
                              size: 16.0,
                              color: Colors.white,
                            ),
                            Text(
                              sosReport.address ?? 'N/A',
                              style: const TextStyle(
                                fontWeight: FontWeight.bold,
                                color: Colors.white,
                              ),
                              maxLines: 2,
                              overflow: TextOverflow.ellipsis,
                            ),
                          ],
                        ),
                        const SizedBox(height: 4),
                        Row(
                          children: [
                            const Icon(
                              Icons.calendar_month,
                              size: 16.0,
                              color: Colors.white,
                            ),
                            Text(
                              sosReport.date,
                              style: const TextStyle(color: Colors.white70),
                            ),
                          ],
                        ),
                      ],
                    ),
                    Image.asset(
                      sosReport.type == 'fire' ? AssetPaths.fire : AssetPaths.home,
                      width: 20.0,
                      height: 20.0,
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
