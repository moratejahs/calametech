import 'package:calamitech/constants/asset_paths.dart';
import 'package:calamitech/features/tips/models/tip_model.dart';
import 'package:flutter/material.dart';

class TipCard extends StatefulWidget {
  final TipModel tip;
  final int index;
  const TipCard({super.key, required this.index, required this.tip});

  @override
  State<TipCard> createState() => _TipCardState();
}

class _TipCardState extends State<TipCard> {
  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(vertical: 8.0, horizontal: 16.0),
      elevation: 4.0,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12.0),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          ClipRRect(
            borderRadius: const BorderRadius.vertical(top: Radius.circular(12.0)),
            child: Image.asset(
              widget.tip.type == 'fire_tips'
                  ? AssetPaths.fire
                  : widget.tip.type == 'flood_tips'
                      ? AssetPaths.home
                      : AssetPaths.safety,
              width: double.infinity,
              height: 160,
              fit: BoxFit.cover,
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: RichText(
              text: TextSpan(
                children: [
                  TextSpan(
                    text: 'Tip #${widget.index} ',
                    style: const TextStyle(
                      fontWeight: FontWeight.bold,
                      fontSize: 16.0,
                      color: Colors.black,
                    ),
                  ),
                  TextSpan(
                    text: widget.tip.content,
                    style: const TextStyle(
                      fontWeight: FontWeight.normal,
                      fontSize: 16.0,
                      color: Colors.black,
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}
