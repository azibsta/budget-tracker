import unittest
def sum_between(n1, n2):
    """Returns the sum of all numbers between n1 and n2 (inclusive)."""
    return sum(range(n1, n2 + 1))

class TestSumBetween(unittest.TestCase):
    def test_positive_numbers(self):
        self.assertEqual(sum_between(1, 5), 15)  # 1+2+3+4+5=15

    def test_negative_numbers(self):
        self.assertEqual(sum_between(-3, 2), -3)  # -3+-2+-1+0+1+2=-3

    def test_single_number(self):
        self.assertEqual(sum_between(5, 5), 5)  # Only 5 in range

    def test_reversed_order(self):
        self.assertEqual(sum_between(5, 1), 15)  # Should work like sum_between(1, 5)

if __name__ == '__main__':
    unittest.main()
