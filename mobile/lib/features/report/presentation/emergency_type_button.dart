import 'package:flutter/material.dart';

class EmergencyTypeButton extends StatelessWidget {
  final String image;
  final String title;
  final VoidCallback onTap;
  final bool isSelected;

  const EmergencyTypeButton({
    super.key,
    required this.image,
    required this.title,
    required this.onTap,
    this.isSelected = false,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 120.0, // Increased width for better spacing
        padding: const EdgeInsets.all(12.0),
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(8.0),
          border: Border.all(color: isSelected ? Colors.red : Colors.grey.shade300, width: isSelected ? 2.0 : 1.0),
          color: isSelected ? Colors.red.withOpacity(0.1) : Colors.white,
        ),
        child: Center(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Image.asset(
                image,
                width: 30.0, // specify the width
                height: 30.0, // specify the height
              ),
              const SizedBox(height: 8.0),
              Text(
                title,
                style: const TextStyle(
                  fontSize: 18.0,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
