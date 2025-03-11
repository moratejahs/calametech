import 'package:calamitech/constants/api_paths.dart';
import 'package:calamitech/features/home/home.dart';
import 'package:flutter/material.dart';

class SosCard extends StatelessWidget {
  final SosReport sosReport;
  final bool fullWidth;

  const SosCard({super.key, required this.sosReport, this.fullWidth = false});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: fullWidth ? MediaQuery.of(context).size.width - 32 : 200,
      margin: const EdgeInsets.only(right: 12),
      child: Card(
        elevation: 3,
        clipBehavior: Clip.antiAlias,
        child: Stack(
          children: [
            Positioned.fill(
              child: sosReport.image != null
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
            ),
            // Semi-transparent overlay for text readability
            // Positioned.fill(
            //   child: Container(
            //     decoration: const BoxDecoration(
            //       gradient: LinearGradient(
            //         begin: Alignment.topCenter,
            //         end: Alignment.bottomCenter,
            //         colors: [
            //           Colors.transparent,
            //           Colors.grey,
            //         ],
            //       ),
            //     ),
            //   ),
            // ),
            // Text content
            Positioned(
              bottom: 0,
              left: 0,
              right: 0,
              child: Padding(
                padding: const EdgeInsets.all(12.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      sosReport.address ?? '',
                      style: const TextStyle(
                        fontWeight: FontWeight.bold,
                        color: Colors.white,
                      ),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    const SizedBox(height: 4),
                    Text(
                      sosReport.date,
                      style: const TextStyle(color: Colors.white70),
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
